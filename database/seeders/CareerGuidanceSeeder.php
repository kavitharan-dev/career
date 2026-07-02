<?php

namespace Database\Seeders;

use App\Models\CareerDomain;
use App\Models\DailyTaskSubtaskTemplate;
use App\Models\DailyTaskTemplate;
use App\Models\RoadmapStepTemplate;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class CareerGuidanceSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            'Programming', 'Problem Solving', 'Logical Thinking', 'Mathematics',
            'Communication', 'Creativity', 'UI Design', 'Data Analysis',
            'Teamwork', 'Research', 'Writing', 'Public Speaking',
        ];

        foreach ($skills as $name) {
            Skill::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name), 'user_id' => null],
                ['name' => $name]
            );
        }

        $domains = [
            [
                'name' => 'Programming',
                'slug' => 'programming',
                'description' => 'Learn programming from zero — Java path with real projects (W3Schools-style steps).',
                'trait_weights' => [
                    'logical' => 25,
                    'problem_solving' => 25,
                    'academics' => 15,
                    'interest_keywords' => ['code', 'software', 'app', 'developer', 'programming', 'java'],
                ],
                'required_skill_slugs' => ['programming', 'problem-solving', 'logical-thinking'],
                'steps' => RoadmapCurriculumData::programming(),
            ],
            [
                'name' => 'Data Science',
                'slug' => 'data-science',
                'description' => 'Analyze data with Python, SQL, statistics, and machine learning.',
                'trait_weights' => [
                    'logical' => 30,
                    'problem_solving' => 20,
                    'academics' => 20,
                    'interest_keywords' => ['data', 'analytics', 'statistics', 'machine', 'ai'],
                ],
                'required_skill_slugs' => ['data-analysis', 'mathematics', 'logical-thinking'],
                'steps' => RoadmapCurriculumData::dataScience(),
            ],
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
                'description' => 'HTML, CSS, JavaScript, and full-stack web apps with Laravel.',
                'trait_weights' => [
                    'logical' => 20,
                    'problem_solving' => 20,
                    'academics' => 10,
                    'interest_keywords' => ['web', 'frontend', 'backend', 'html', 'website'],
                ],
                'required_skill_slugs' => ['programming', 'ui-design', 'creativity'],
                'steps' => RoadmapCurriculumData::webDevelopment(),
            ],
            [
                'name' => 'Design',
                'slug' => 'design',
                'description' => 'Visual design, UX, Figma, and portfolio case studies.',
                'trait_weights' => [
                    'logical' => 10,
                    'problem_solving' => 15,
                    'academics' => 10,
                    'interest_keywords' => ['design', 'creative', 'ui', 'ux', 'visual', 'art'],
                ],
                'required_skill_slugs' => ['ui-design', 'creativity', 'communication'],
                'steps' => RoadmapCurriculumData::design(),
            ],
            [
                'name' => 'Cybersecurity',
                'slug' => 'cybersecurity',
                'description' => 'Ethical hacking basics, OWASP, Linux, and security operations.',
                'trait_weights' => [
                    'logical' => 28,
                    'problem_solving' => 22,
                    'academics' => 15,
                    'interest_keywords' => ['security', 'cyber', 'network', 'hack', 'protect'],
                ],
                'required_skill_slugs' => ['logical-thinking', 'problem-solving', 'programming'],
                'steps' => RoadmapCurriculumData::cybersecurity(),
            ],
            [
                'name' => 'Mobile Development',
                'slug' => 'mobile-development',
                'description' => 'Android/Kotlin or Flutter — build and publish mobile apps.',
                'trait_weights' => [
                    'logical' => 22,
                    'problem_solving' => 22,
                    'academics' => 12,
                    'interest_keywords' => ['mobile', 'android', 'ios', 'app'],
                ],
                'required_skill_slugs' => ['programming', 'ui-design', 'problem-solving'],
                'steps' => RoadmapCurriculumData::mobileDevelopment(),
            ],
            [
                'name' => 'Digital Marketing',
                'slug' => 'digital-marketing',
                'description' => 'SEO, content, ads, and analytics for real campaigns.',
                'trait_weights' => [
                    'logical' => 12,
                    'problem_solving' => 18,
                    'academics' => 12,
                    'interest_keywords' => ['marketing', 'business', 'social', 'brand', 'seo'],
                ],
                'required_skill_slugs' => ['communication', 'creativity', 'writing'],
                'steps' => RoadmapCurriculumData::digitalMarketing(),
            ],
            [
                'name' => 'Cloud & DevOps',
                'slug' => 'cloud-devops',
                'description' => 'Linux, Docker, CI/CD, and cloud deployment.',
                'trait_weights' => [
                    'logical' => 26,
                    'problem_solving' => 24,
                    'academics' => 14,
                    'interest_keywords' => ['cloud', 'devops', 'server', 'deploy', 'aws'],
                ],
                'required_skill_slugs' => ['programming', 'problem-solving', 'logical-thinking'],
                'steps' => RoadmapCurriculumData::cloudDevops(),
            ],
            [
                'name' => 'AI Engineering',
                'slug' => 'ai-engineering',
                'description' => 'Real AI/ML path: Python, sklearn, PyTorch, LLMs, RAG, and deploy APIs.',
                'trait_weights' => [
                    'logical' => 30,
                    'problem_solving' => 28,
                    'academics' => 18,
                    'interest_keywords' => ['ai', 'machine', 'ml', 'model', 'neural'],
                ],
                'required_skill_slugs' => ['programming', 'data-analysis', 'mathematics', 'logical-thinking'],
                'steps' => RoadmapCurriculumData::aiEngineering(),
            ],
        ];

        foreach ($domains as $data) {
            $steps = $data['steps'];
            unset($data['steps']);

            $domain = CareerDomain::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge(
                    $data,
                    CareerDomainGuidanceData::forSlug($data['slug']),
                    ['sri_lanka_jobs' => CareerDomainGuidanceData::sriLankaJobs($data['slug'])]
                )
            );

            foreach ($steps as $stepData) {
                $tasks = $stepData['tasks'];
                unset($stepData['tasks']);

                $step = RoadmapStepTemplate::updateOrCreate(
                    [
                        'career_domain_id' => $domain->id,
                        'sort_order' => $stepData['sort_order'],
                    ],
                    array_merge($stepData, ['career_domain_id' => $domain->id])
                );

                $keptTaskTemplateIds = [];

                foreach ($tasks as $task) {
                    $taskTemplate = DailyTaskTemplate::updateOrCreate(
                        [
                            'roadmap_step_template_id' => $step->id,
                            'day_number' => $task['day_number'],
                        ],
                        [
                            'title' => $task['title'],
                            'description' => $task['description'],
                            'roadmap_step_template_id' => $step->id,
                        ]
                    );

                    $keptTaskTemplateIds[] = $taskTemplate->id;
                    $this->seedSubtasksForTemplate($taskTemplate, $task);
                }

                DailyTaskTemplate::query()
                    ->where('roadmap_step_template_id', $step->id)
                    ->whereNotIn('id', $keptTaskTemplateIds)
                    ->each(function (DailyTaskTemplate $obsolete) {
                        $obsolete->subtaskTemplates()->delete();
                        $obsolete->delete();
                    });
            }
        }

        $this->call(CareerFitQuestionSeeder::class);
    }

    /**
     * @param  array<string, mixed>  $task
     */
    protected function seedSubtasksForTemplate(DailyTaskTemplate $template, array $task): void
    {
        $titles = $task['subtasks'] ?? [
            'Study the lesson material for this topic',
            'Complete the hands-on exercise described',
            'Self-check: explain what you learned in your own words',
        ];

        $keptIds = [];

        foreach ($titles as $index => $title) {
            $sub = DailyTaskSubtaskTemplate::updateOrCreate(
                [
                    'daily_task_template_id' => $template->id,
                    'sort_order' => $index + 1,
                ],
                ['title' => $title]
            );
            $keptIds[] = $sub->id;
        }

        DailyTaskSubtaskTemplate::query()
            ->where('daily_task_template_id', $template->id)
            ->whereNotIn('id', $keptIds)
            ->delete();
    }
}
