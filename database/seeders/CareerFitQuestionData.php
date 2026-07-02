<?php

namespace Database\Seeders;

/**
 * Unique AI-style fit questionnaires — 6 questions per career, scored per option.
 */
class CareerFitQuestionData
{
    public static function all(): array
    {
        return [
            'programming' => self::programming(),
            'data-science' => self::dataScience(),
            'web-development' => self::webDevelopment(),
            'design' => self::design(),
            'cybersecurity' => self::cybersecurity(),
            'mobile-development' => self::mobileDevelopment(),
            'digital-marketing' => self::digitalMarketing(),
            'cloud-devops' => self::cloudDevops(),
            'ai-engineering' => self::aiEngineering(),
        ];
    }

    /** @return array<int, array{key: string, text: string, options: array<string, array{label: string, points: int}>}> */
    private static function programming(): array
    {
        return [
            self::q('enjoy_coding', 'How do you feel about writing and reading code for several hours?', [
                'a' => ['Love it — I lose track of time', 10],
                'b' => ['Enjoy it with breaks', 7],
                'c' => ['Tolerate it when needed', 3],
                'd' => ['Prefer not to code daily', 0],
            ]),
            self::q('debug_mindset', 'When a program fails, you usually:', [
                'a' => ['Trace logs/errors systematically until fixed', 10],
                'b' => ['Search Stack Overflow and try fixes', 7],
                'c' => ['Ask someone else immediately', 3],
                'd' => ['Give up or switch tasks', 0],
            ]),
            self::q('learn_languages', 'Learning a new programming language (e.g. Java) feels:', [
                'a' => ['Exciting — I want to master it', 10],
                'b' => ['Useful for my career goals', 7],
                'c' => ['Hard but I will try', 4],
                'd' => ['Not something I want to do', 0],
            ]),
            self::q('team_dev', 'Working with Git, code reviews, and team sprints:', [
                'a' => ['Sounds like how I want to work', 10],
                'b' => ['Fine after some practice', 6],
                'c' => ['Prefer solo projects only', 3],
                'd' => ['Not interested', 0],
            ]),
            self::q('math_cs', 'Your interest in Math / Computer Science subjects:', [
                'a' => ['Strong — among my best subjects', 10],
                'b' => ['Average but improving', 6],
                'c' => ['Weak but willing to learn', 3],
                'd' => ['Not relevant to me', 0],
            ]),
            self::q('side_projects', 'Have you built any software project (school, hobby, or internship)?', [
                'a' => ['Yes — multiple or published apps/sites', 10],
                'b' => ['Yes — at least one small project', 7],
                'c' => ['Only tutorials, no own project yet', 4],
                'd' => ['No and no plan to', 0],
            ]),
        ];
    }

    private static function dataScience(): array
    {
        return [
            self::q('love_numbers', 'Working with spreadsheets, charts, and numbers:', [
                'a' => ['One of my favourite activities', 10],
                'b' => ['Comfortable for work tasks', 7],
                'c' => ['Only when required', 3],
                'd' => ['I avoid data work', 0],
            ]),
            self::q('stats_interest', 'Statistics (mean, correlation, probability) feels:', [
                'a' => ['Interesting — I want to go deeper', 10],
                'b' => ['OK with practice', 6],
                'c' => ['Difficult and boring', 2],
                'd' => ['I do not want this in my job', 0],
            ]),
            self::q('data_story', 'Explaining insights from data to non-technical people:', [
                'a' => ['I enjoy presenting findings clearly', 10],
                'b' => ['I can learn to do it', 6],
                'c' => ['Prefer only coding models', 4],
                'd' => ['Not for me', 0],
            ]),
            self::q('python_sql', 'Learning Python and SQL for analytics:', [
                'a' => ['Already started or very motivated', 10],
                'b' => ['Will learn for career', 7],
                'c' => ['Unsure', 3],
                'd' => ['Prefer no programming', 0],
            ]),
            self::q('pattern_hunt', 'When you see trends in sales or exam results, you:', [
                'a' => ['Ask why and dig for causes', 10],
                'b' => ['Notice patterns sometimes', 6],
                'c' => ['Rarely think about it', 2],
                'd' => ['Do not care', 0],
            ]),
            self::q('research_patience', 'Cleaning messy data before analysis takes days. You:', [
                'a' => ['Accept it as essential work', 10],
                'b' => ['Tolerate with breaks', 6],
                'c' => ['Find it frustrating', 2],
                'd' => ['Would quit such a role', 0],
            ]),
        ];
    }

    private static function webDevelopment(): array
    {
        return [
            self::q('visible_results', 'Seeing a website you built live on screen:', [
                'a' => ['Highly motivating', 10],
                'b' => ['Nice reward', 7],
                'c' => ['Neutral', 3],
                'd' => ['Does not matter to me', 0],
            ]),
            self::q('html_css', 'HTML/CSS layout and responsive design:', [
                'a' => ['Enjoy tweaking until it looks perfect', 10],
                'b' => ['OK with frameworks help', 6],
                'c' => ['Prefer backend only', 4],
                'd' => ['Avoid front-end', 0],
            ]),
            self::q('js_interest', 'JavaScript interactivity (forms, APIs, SPAs):', [
                'a' => ['Want to master it', 10],
                'b' => ['Learn as needed', 6],
                'c' => ['Minimal interest', 2],
                'd' => ['No interest', 0],
            ]),
            self::q('client_work', 'Building sites for local businesses or portfolios:', [
                'a' => ['Already done or eager to', 10],
                'b' => ['Would try freelancing', 7],
                'c' => ['Only employee role', 4],
                'd' => ['Not interested', 0],
            ]),
            self::q('design_sense', 'Matching colours, fonts, and UX basics:', [
                'a' => ['Care about design quality', 10],
                'b' => ['Use templates and improve', 6],
                'c' => ['Function over form only', 3],
                'd' => ['No design interest', 0],
            ]),
            self::q('deploy_host', 'Deploying to hosting (cPanel, Laravel, Vercel):', [
                'a' => ['Want to learn DevOps basics too', 9],
                'b' => ['Will follow guides', 6],
                'c' => ['Someone else should deploy', 2],
                'd' => ['Not my responsibility', 0],
            ]),
        ];
    }

    private static function design(): array
    {
        return [
            self::q('visual_taste', 'You notice typography, spacing, and colour on apps/ads:', [
                'a' => ['Always — I critique details', 10],
                'b' => ['Sometimes', 6],
                'c' => ['Rarely', 2],
                'd' => ['Never', 0],
            ]),
            self::q('figma_tools', 'Using Figma or similar design tools:', [
                'a' => ['Already use or excited to learn', 10],
                'b' => ['Will learn for career', 7],
                'c' => ['Prefer pen and paper only', 4],
                'd' => ['Avoid design software', 0],
            ]),
            self::q('user_empathy', 'Interviewing users about pain points:', [
                'a' => ['Enjoy understanding people', 10],
                'b' => ['Can do with training', 6],
                'c' => ['Prefer only visual work', 3],
                'd' => ['Uncomfortable with users', 0],
            ]),
            self::q('portfolio', 'A design portfolio (Behance, case studies):', [
                'a' => ['I have or am building one', 10],
                'b' => ['Plan to create soon', 7],
                'c' => ['No plan yet', 3],
                'd' => ['Not needed for me', 0],
            ]),
            self::q('creativity_daily', 'Creative work (illustration, branding, UI) daily:', [
                'a' => ['Ideal career for me', 10],
                'b' => ['Good mix with other tasks', 6],
                'c' => ['Occasional only', 2],
                'd' => ['Prefer non-creative jobs', 0],
            ]),
            self::q('dev_handoff', 'Working with developers to implement designs:', [
                'a' => ['Comfortable collaborating', 10],
                'b' => ['Learning communication', 6],
                'c' => ['Prefer solo design only', 3],
                'd' => ['Avoid tech teams', 0],
            ]),
        ];
    }

    private static function cybersecurity(): array
    {
        return [
            self::q('security_curiosity', 'When you hear about a data breach, you:', [
                'a' => ['Read how the attack worked', 10],
                'b' => ['Wonder if your data is safe', 6],
                'c' => ['Ignore tech news', 1],
                'd' => ['Not interested', 0],
            ]),
            self::q('ethical_line', 'Using hacking skills only with permission:', [
                'a' => ['Strong ethics — non-negotiable', 10],
                'b' => ['Understand rules', 7],
                'c' => ['Gray area OK sometimes', 0],
                'd' => ['Would break rules for fun', 0],
            ]),
            self::q('detail_logs', 'Reading long logs and alert dashboards:', [
                'a' => ['Patient and thorough', 10],
                'b' => ['Can learn discipline', 6],
                'c' => ['Find it tedious', 2],
                'd' => ['Cannot focus on details', 0],
            ]),
            self::q('networking_base', 'Networking (IP, DNS, firewalls, Linux):', [
                'a' => ['Studying or already know basics', 10],
                'b' => ['Will study seriously', 7],
                'c' => ['Only surface level', 3],
                'd' => ['No interest', 0],
            ]),
            self::q('stress_incidents', 'Handling security incidents under pressure:', [
                'a' => ['Stay calm and follow runbooks', 10],
                'b' => ['Stressful but manageable', 6],
                'c' => ['High anxiety', 2],
                'd' => ['Avoid high-pressure roles', 0],
            ]),
            self::q('cert_path', 'Certs like Security+, CEH, or local bank training:', [
                'a' => ['Actively pursuing', 10],
                'b' => ['Plan after fundamentals', 7],
                'c' => ['Maybe later', 3],
                'd' => ['No certifications', 0],
            ]),
        ];
    }

    private static function mobileDevelopment(): array
    {
        return [
            self::q('phone_apps_daily', 'You use mobile apps and think about how they work:', [
                'a' => ['Often imagine building my own', 10],
                'b' => ['Sometimes curious', 6],
                'c' => ['Just use them', 2],
                'd' => ['Prefer desktop/web only', 0],
            ]),
            self::q('ui_mobile', 'Designing UI for small screens (Android/iOS):', [
                'a' => ['Enjoy layout challenges', 10],
                'b' => ['OK with guidelines', 6],
                'c' => ['Prefer backend/API', 4],
                'd' => ['Avoid UI work', 0],
            ]),
            self::q('kotlin_flutter', 'Learning Kotlin, Flutter, or Swift:', [
                'a' => ['Started or very motivated', 10],
                'b' => ['Will commit to one stack', 7],
                'c' => ['Undecided', 3],
                'd' => ['No mobile coding', 0],
            ]),
            self::q('play_store', 'Publishing an app to Play Store / App Store:', [
                'a' => ['Goal I am working toward', 10],
                'b' => ['Want to try once', 7],
                'c' => ['Only class projects', 4],
                'd' => ['Not interested', 0],
            ]),
            self::q('device_testing', 'Testing on real devices and fixing crashes:', [
                'a' => ['Systematic debugger', 10],
                'b' => ['Patient enough', 6],
                'c' => ['Frustrating', 2],
                'd' => ['Avoid testing', 0],
            ]),
            self::q('sl_apps', 'Sri Lankan apps (banking, delivery, tourism) interest you because:', [
                'a' => ['I want to build similar products', 10],
                'b' => ['Good job market', 7],
                'c' => ['Just a job option', 3],
                'd' => ['No local focus', 0],
            ]),
        ];
    }

    private static function digitalMarketing(): array
    {
        return [
            self::q('social_content', 'Creating posts, reels, or ad copy:', [
                'a' => ['Enjoy and do regularly', 10],
                'b' => ['Comfortable for work', 7],
                'c' => ['Only when forced', 2],
                'd' => ['Dislike social media work', 0],
            ]),
            self::q('metrics_roi', 'Tracking clicks, conversions, and ROI:', [
                'a' => ['Love optimizing campaigns', 10],
                'b' => ['Will learn analytics tools', 7],
                'c' => ['Prefer creative only', 4],
                'd' => ['Avoid numbers', 0],
            ]),
            self::q('writing_persuade', 'Writing to persuade customers (English/Sinhala):', [
                'a' => ['Strength of mine', 10],
                'b' => ['Improving with practice', 6],
                'c' => ['Weak but trying', 3],
                'd' => ['Avoid writing jobs', 0],
            ]),
            self::q('seo_ads', 'SEO and paid ads (Google, Meta):', [
                'a' => ['Studying or certified', 10],
                'b' => ['Want certification', 7],
                'c' => ['Heard of them only', 3],
                'd' => ['No interest', 0],
            ]),
            self::q('client_calls', 'Talking to SME clients in Sri Lanka about marketing:', [
                'a' => ['Confident communicator', 10],
                'b' => ['Can learn sales skills', 6],
                'c' => ['Prefer behind-screen only', 3],
                'd' => ['Avoid client contact', 0],
            ]),
            self::q('business_interest', 'Interest in Business / Entrepreneurship:', [
                'a' => ['Core interest', 10],
                'b' => ['Some interest', 6],
                'c' => ['Tech only', 2],
                'd' => ['Not relevant', 0],
            ]),
        ];
    }

    private static function cloudDevops(): array
    {
        return [
            self::q('servers_linux', 'Linux servers and command line:', [
                'a' => ['Comfortable or learning fast', 10],
                'b' => ['Will practice daily', 7],
                'c' => ['Windows only so far', 3],
                'd' => ['Avoid terminal', 0],
            ]),
            self::q('automation', 'Writing scripts to automate repetitive tasks:', [
                'a' => ['Already automate things', 10],
                'b' => ['Want this as main work', 7],
                'c' => ['Rarely', 2],
                'd' => ['Manual work is fine', 0],
            ]),
            self::q('uptime_duty', 'Being on-call when systems go down at night:', [
                'a' => ['Accept for career growth', 10],
                'b' => ['OK occasionally', 6],
                'c' => ['Prefer 9–5 only', 3],
                'd' => ['Cannot do on-call', 0],
            ]),
            self::q('docker_cloud', 'Docker, AWS/Azure, CI/CD pipelines:', [
                'a' => ['Learning or using now', 10],
                'b' => ['Plan structured learning', 7],
                'c' => ['Heard names only', 3],
                'd' => ['Not interested', 0],
            ]),
            self::q('infra_not_ui', 'You prefer infrastructure over building user interfaces:', [
                'a' => ['Strongly prefer infra', 10],
                'b' => ['Mix of both', 6],
                'c' => ['Prefer UI/apps', 2],
                'd' => ['Neither', 0],
            ]),
            self::q('reliability', 'When a site is slow, you think about:', [
                'a' => ['Caching, scaling, monitoring', 10],
                'b' => ['Ask IT to fix', 5],
                'c' => ['Refresh browser', 2],
                'd' => ['Not my problem', 0],
            ]),
        ];
    }

    private static function aiEngineering(): array
    {
        return [
            self::q('math_ml', 'Linear algebra, calculus, or ML math:', [
                'a' => ['Enjoy or doing well', 10],
                'b' => ['Will study hard for AI', 7],
                'c' => ['Struggle but try', 3],
                'd' => ['Want to avoid math', 0],
            ]),
            self::q('models_not_only_chat', 'Building/training models vs only using ChatGPT:', [
                'a' => ['Want to build models myself', 10],
                'b' => ['Learn both use and build', 7],
                'c' => ['Only use AI tools', 3],
                'd' => ['No AI interest', 0],
            ]),
            self::q('datasets', 'Working with large datasets and experiments:', [
                'a' => ['Patient experimenter', 10],
                'b' => ['OK with iteration', 6],
                'c' => ['Impatient for results', 2],
                'd' => ['Avoid data prep', 0],
            ]),
            self::q('python_ml_stack', 'Python, NumPy, scikit-learn, PyTorch/TensorFlow:', [
                'a' => ['Already learning stack', 10],
                'b' => ['Committed learning path', 7],
                'c' => ['Beginner only', 4],
                'd' => ['No Python', 0],
            ]),
            self::q('deploy_models', 'Deploying models as APIs (MLOps):', [
                'a' => ['Interested in full pipeline', 10],
                'b' => ['Research side first', 6],
                'c' => ['Theory only', 3],
                'd' => ['Not interested', 0],
            ]),
            self::q('ai_interest', 'Interest in AI / Machine Learning topics:', [
                'a' => ['Top career passion', 10],
                'b' => ['Strong among my interests', 7],
                'c' => ['Mild curiosity', 3],
                'd' => ['Not interested', 0],
            ]),
        ];
    }

    /**
     * @param  array<string, array{0: string, 1: int}>  $choices
     * @return array{key: string, text: string, options: array<string, array{label: string, points: int}>}
     */
    private static function q(string $key, string $text, array $choices): array
    {
        $options = [];
        foreach ($choices as $letter => [$label, $points]) {
            $options[$letter] = ['label' => $label, 'points' => $points];
        }

        return ['key' => $key, 'text' => $text, 'options' => $options];
    }
}
