<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\DailyTask;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatbotService
{
    public function __construct(protected AdminSystemStatsService $adminStats) {}

    public function reply(User $user, string $message): string
    {
        $normalized = Str::lower(trim($message));

        ChatMessage::create([
            'user_id' => $user->id,
            'role' => 'user',
            'message' => $message,
        ]);

        $reply = $this->shouldUseOpenAi()
            ? $this->replyWithOpenAi($user, $message)
            : ($this->shouldUseGroq()
                ? $this->replyWithGroq($user, $message)
                : ($this->shouldUseGemini()
                    ? $this->replyWithGemini($user, $message)
                    : ($user->is_admin
                        ? $this->replyWithAdminRules($normalized)
                        : $this->replyWithRules($user, $normalized))));

        ChatMessage::create([
            'user_id' => $user->id,
            'role' => 'assistant',
            'message' => $reply,
        ]);

        return $reply;
    }

    protected function shouldUseOpenAi(): bool
    {
        return (bool) config('services.openai.enabled')
            && (bool) config('services.openai.api_key');
    }

    protected function shouldUseGroq(): bool
    {
        return (bool) config('services.groq.enabled')
            && (bool) config('services.groq.api_key');
    }

    protected function shouldUseGemini(): bool
    {
        return (bool) config('services.gemini.enabled')
            && (bool) config('services.gemini.api_key');
    }

    protected function replyWithOpenAi(User $user, string $message): string
    {
        return $this->replyWithChatCompletions(
            $user,
            $message,
            (string) config('services.openai.api_key'),
            (string) config('services.openai.model', 'gpt-4o-mini'),
            rtrim((string) config('services.openai.base_url', 'https://api.openai.com/v1'), '/'),
            'OpenAI',
        );
    }

    protected function replyWithGroq(User $user, string $message): string
    {
        return $this->replyWithChatCompletions(
            $user,
            $message,
            (string) config('services.groq.api_key'),
            (string) config('services.groq.model', 'llama-3.3-70b-versatile'),
            rtrim((string) config('services.groq.base_url', 'https://api.groq.com/openai/v1'), '/'),
            'Groq',
        );
    }

    protected function replyWithGemini(User $user, string $message): string
    {
        try {
            $apiKey = (string) config('services.gemini.api_key');
            $model = (string) config('services.gemini.model', 'gemini-2.0-flash');
            $baseUrl = rtrim((string) config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta'), '/');
            $systemPrompt = $user->is_admin
                ? $this->buildAdminSystemPrompt($user)
                : $this->buildSystemPrompt($user);

            $response = Http::timeout(30)
                ->acceptJson()
                ->post("{$baseUrl}/models/{$model}:generateContent?key={$apiKey}", [
                    'system_instruction' => [
                        'parts' => [
                            ['text' => $systemPrompt],
                        ],
                    ],
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $message],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.5,
                        'maxOutputTokens' => 512,
                    ],
                ]);

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text');

                if (is_string($text) && trim($text) !== '') {
                    return trim($text);
                }
            }

            Log::warning('Gemini empty or failed response', [
                'status' => $response->status(),
                'body' => $response->json() ?? $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Gemini API error', ['error' => $e->getMessage()]);
        }

        $normalized = Str::lower(trim($message));

        return $user->is_admin
            ? $this->replyWithAdminRules($normalized)
            : $this->replyWithRules($user, $normalized);
    }

    protected function replyWithChatCompletions(
        User $user,
        string $message,
        string $apiKey,
        string $model,
        string $baseUrl,
        string $provider,
    ): string {
        try {
            $systemPrompt = $user->is_admin
                ? $this->buildAdminSystemPrompt($user)
                : $this->buildSystemPrompt($user);

            $response = Http::timeout(30)
                ->withToken($apiKey)
                ->acceptJson()
                ->post("{$baseUrl}/chat/completions", [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $message],
                    ],
                    'max_tokens' => 512,
                    'temperature' => 0.5,
                ]);

            if ($response->successful()) {
                $text = $response->json('choices.0.message.content');

                if (is_string($text) && trim($text) !== '') {
                    return trim($text);
                }
            }

            Log::warning("{$provider} empty or failed response", [
                'status' => $response->status(),
                'body' => $response->json() ?? $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::warning("{$provider} API error", ['error' => $e->getMessage()]);
        }

        $normalized = Str::lower(trim($message));

        return $user->is_admin
            ? $this->replyWithAdminRules($normalized)
            : $this->replyWithRules($user, $normalized);
    }

    protected function buildAdminSystemPrompt(User $user): string
    {
        $context = $this->adminStats->contextBlock();

        return <<<PROMPT
You are Arivexa Admin Assistant — a system helper for the Arivexa career guidance platform administrator.

Admin user: {$user->name} ({$user->email})

{$context}

Your job:
- Answer questions about the platform: student registrations, onboarding, roadmaps, curriculum, career domains, task completion, and system stats.
- Always use the live numbers above when the admin asks "how many", "count", "registered", "pending", etc.
- Explain admin actions clearly: reseed curriculum, regenerate roadmaps, manage students, edit career domains.
- Keep answers concise (under 150 words) unless the admin asks for detail.
- Tamil or English is fine if the admin writes in Tamil.
- Do NOT give student career tutoring — you are for system/admin queries only.
- If data is not in the snapshot, say which admin page to check (Students, Curriculum tools, Career domains).
PROMPT;
    }

    protected function buildSystemPrompt(User $user): string
    {
        $user->loadMissing(['studentProfile', 'primaryRecommendation.careerDomain', 'activeRoadmap']);

        $career = $user->primaryRecommendation?->careerDomain?->name ?? 'not set yet';
        $match = $user->primaryRecommendation?->match_score ?? 0;
        $progress = $user->activeRoadmap?->completion_percentage ?? 0;
        $logical = $user->studentProfile?->logical_score ?? 0;
        $problem = $user->studentProfile?->problem_solving_score ?? 0;

        $pendingTask = DailyTask::query()
            ->where('status', '!=', DailyTask::STATUS_COMPLETED)
            ->whereHas('roadmapStep', function ($q) use ($user) {
                $q->where('is_unlocked', true)
                    ->whereHas('roadmap', fn ($r) => $r->where('user_id', $user->id));
            })
            ->orderBy('day_number')
            ->value('title') ?? 'none';

        return <<<PROMPT
You are Arivexa Tutor, a friendly career and learning assistant for Sri Lankan students.

Student context:
- Name: {$user->name}
- Top career match: {$career} ({$match}%)
- Roadmap progress: {$progress}%
- Logical score: {$logical}%
- Problem solving: {$problem}%
- Next pending task: {$pendingTask}

Rules:
- Answer in clear, simple English (Tamil words OK if student uses Tamil).
- Focus on career guidance, learning roadmap, skills, and study advice.
- Use the student context above when relevant.
- Keep answers under 120 words unless the student asks for detail.
- If asked something unrelated to careers/learning, politely redirect.
PROMPT;
    }

    protected function replyWithAdminRules(string $normalized): string
    {
        $s = $this->adminStats->snapshot();

        return match (true) {
            Str::contains($normalized, ['hello', 'hi', 'hey', 'vanakkam']) => "Hello! I'm the Arivexa Admin Assistant. Ask me about registered students, onboarding, roadmaps, curriculum, or platform stats.",
            Str::contains($normalized, ['your name', 'who are you', 'what are you']) => 'I am the Arivexa Admin Assistant — I answer system and platform questions using live data from your database.',
            Str::contains($normalized, ['student', 'registered', 'registration', 'users', 'how many']) => "There are {$s['total_students']} registered students. Onboarding complete: {$s['onboarding_complete']}. Still pending: {$s['onboarding_pending']}. Students with roadmaps: {$s['students_with_roadmaps']}.",
            Str::contains($normalized, ['onboarding', 'pending profile']) => "Onboarding completed: {$s['onboarding_complete']} students. Pending onboarding: {$s['onboarding_pending']}. Check Admin → Students for details.",
            Str::contains($normalized, ['roadmap']) => "Students with roadmaps: {$s['students_with_roadmaps']} out of {$s['total_students']}. Curriculum has {$s['roadmap_steps']} steps and {$s['lesson_tasks']} lessons. Regenerate roadmaps from Admin → Curriculum tools if needed.",
            Str::contains($normalized, ['curriculum', 'lesson', 'subtask', 'step template']) => "Curriculum: {$s['career_domains']} career domains, {$s['roadmap_steps']} roadmap steps, {$s['lesson_tasks']} lessons, {$s['subtasks']} subtasks. Reseed from Admin → Curriculum tools.",
            Str::contains($normalized, ['domain', 'career path', 'career']) => "The system has {$s['career_domains']} career domains. Top student matches: {$s['top_careers']}. Manage them under Admin → Career domains.",
            Str::contains($normalized, ['recommendation', 'match']) => "Total career recommendations: {$s['recommendations']}. Top careers: {$s['top_careers']}.",
            Str::contains($normalized, ['task', 'complete', 'progress']) => "Students completed {$s['completed_tasks']} tasks. {$s['pending_tasks']} tasks are still pending across all roadmaps.",
            Str::contains($normalized, ['recent', 'latest', 'new student']) => "Recently registered: {$s['recent_students']}. Full list: Admin → Students.",
            Str::contains($normalized, ['system', 'platform', 'overview', 'summary', 'stats']) => $this->adminOverview($s),
            Str::contains($normalized, ['reseed', 'regenerate']) => 'Reseed curriculum: Admin → Curriculum tools → "Reseed curriculum" (updates templates only). Regenerate roadmaps: same page → "Regenerate all roadmaps" (resets student task progress).',
            default => "I can answer system questions using live data. Try: \"How many students registered?\" or \"System overview\". Current students: {$s['total_students']}.",
        };
    }

    /**
     * @param  array<string, int|string>  $s
     */
    protected function adminOverview(array $s): string
    {
        return "Arivexa system overview: {$s['total_students']} students ({$s['onboarding_complete']} onboarded, {$s['onboarding_pending']} pending). {$s['career_domains']} careers, {$s['recommendations']} recommendations. Tasks: {$s['completed_tasks']} done / {$s['pending_tasks']} pending. Top careers: {$s['top_careers']}.";
    }

    protected function replyWithRules(User $user, string $normalized): string
    {
        return match (true) {
            Str::contains($normalized, ['hello', 'hi', 'hey', 'vanakkam']) => $this->greeting($user),
            Str::contains($normalized, ['your name', 'who are you', 'what is your name', 'what are you']) => $this->identity($user),
            Str::contains($normalized, ['career', 'domain', 'path', 'field']) => $this->careerAdvice($user),
            Str::contains($normalized, ['next', 'step', 'task', 'today']) => $this->nextStep($user),
            Str::contains($normalized, ['progress', 'percent', 'complete']) => $this->progress($user),
            Str::contains($normalized, ['skill', 'test', 'score']) => $this->skillInsight($user),
            Str::contains($normalized, ['roadmap', 'plan', 'learn']) => $this->roadmapHelp($user),
            default => $this->defaultHelp($user),
        };
    }

    protected function greeting(User $user): string
    {
        return "Hello {$user->name}! I'm Arivexa Tutor, your learning assistant. Ask me about your career match, next task, or learning progress.";
    }

    protected function identity(User $user): string
    {
        return "I'm Arivexa Tutor — the learning robot on this Arivexa career guidance platform. I help you, {$user->name}, with career advice, roadmap tasks, skills, and study progress.";
    }

    protected function careerAdvice(User $user): string
    {
        $rec = $user->primaryRecommendation?->load('careerDomain');

        if (! $rec) {
            return 'Complete your profile and skill test first—I need your data to recommend a career domain.';
        }

        return "Your top career match is {$rec->careerDomain->name} ({$rec->match_score}% fit). {$rec->reasoning}";
    }

    protected function nextStep(User $user): string
    {
        $task = DailyTask::query()
            ->where('status', '!=', DailyTask::STATUS_COMPLETED)
            ->whereHas('roadmapStep', function ($q) use ($user) {
                $q->where('is_unlocked', true)
                    ->whereHas('roadmap', fn ($r) => $r->where('user_id', $user->id));
            })
            ->orderBy('day_number')
            ->first();

        if (! $task) {
            return 'Great work—you have no pending tasks in your current unlocked steps. Check your roadmap for newly unlocked levels.';
        }

        $task->load('subtasks');
        $nextSubtask = $task->subtasks->firstWhere('is_completed', false);

        if ($nextSubtask) {
            return "Next for \"{$task->title}\": complete the subtask \"{$nextSubtask->title}\". Finish all subtasks (read → practice → review) to complete the lesson.";
        }

        return "Your next task: \"{$task->title}\". {$task->description}";
    }

    protected function progress(User $user): string
    {
        $roadmap = $user->activeRoadmap;

        if (! $roadmap) {
            return 'You do not have an active roadmap yet. Finish onboarding to generate one.';
        }

        return "Your roadmap completion is {$roadmap->completion_percentage}%. Keep completing daily tasks to unlock advanced steps.";
    }

    protected function skillInsight(User $user): string
    {
        $profile = $user->studentProfile;

        if (! $profile) {
            return 'Add your skills in onboarding so I can personalize guidance.';
        }

        $skills = $user->skills()->pluck('name')->take(5)->implode(', ');

        return "Logical thinking: {$profile->logical_score}%. Problem solving: {$profile->problem_solving_score}%. Key skills: ".($skills ?: 'none added yet').'.';
    }

    protected function roadmapHelp(User $user): string
    {
        $roadmap = $user->activeRoadmap?->load('steps');

        if (! $roadmap) {
            return 'After onboarding, Arivexa builds a step-by-step roadmap from beginner to advanced for your chosen career.';
        }

        $current = $roadmap->steps->firstWhere('is_unlocked', true);

        return "You are on \"{$roadmap->title}\". Current focus: ".($current?->title ?? 'starting soon').'. Open the Roadmap page to see all levels.';
    }

    protected function defaultHelp(User $user): string
    {
        $name = $user->primaryRecommendation?->careerDomain?->name ?? 'your goals';

        return "I can help with career advice, next tasks, and progress for {$name}. Try asking: \"What is my next step?\" or \"Show my progress.\"";
    }
}
