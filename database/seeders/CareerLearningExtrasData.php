<?php

namespace Database\Seeders;

/**
 * Interview prep questions and free learning resources per career.
 */
class CareerLearningExtrasData
{
    /**
     * @return array<int, array{question: string, tip: string}>
     */
    public static function interviewQuestions(string $slug): array
    {
        return match ($slug) {
            'programming' => [
                ['question' => 'Explain OOP concepts: encapsulation, inheritance, polymorphism.', 'tip' => 'Use a simple Java class example from your roadmap projects.'],
                ['question' => 'What is the difference between an abstract class and an interface?', 'tip' => 'Mention when you would use each in a real project.'],
                ['question' => 'How do you debug a production issue?', 'tip' => 'Talk about logs, breakpoints, and reproducing the bug locally.'],
                ['question' => 'Describe a coding project you built.', 'tip' => 'Use your GitHub portfolio — explain problem, solution, and tech stack.'],
                ['question' => 'What is Big-O notation and why does it matter?', 'tip' => 'Give an example: O(n) vs O(n²) with a loop.'],
            ],
            'data-science' => [
                ['question' => 'Explain the difference between supervised and unsupervised learning.', 'tip' => 'Use classification vs clustering examples.'],
                ['question' => 'How do you handle missing data in a dataset?', 'tip' => 'Mention imputation, dropping rows, and when each is appropriate.'],
                ['question' => 'What is overfitting and how do you prevent it?', 'tip' => 'Discuss train/test split, cross-validation, and regularization.'],
                ['question' => 'Walk us through a data analysis project.', 'tip' => 'Follow: question → data → cleaning → visualization → insight.'],
                ['question' => 'Explain precision vs recall.', 'tip' => 'Use a fraud detection or medical test analogy.'],
            ],
            'web-development' => [
                ['question' => 'Explain the difference between HTML, CSS, and JavaScript.', 'tip' => 'Structure vs style vs behaviour — give a page example.'],
                ['question' => 'What is responsive design?', 'tip' => 'Mention media queries, flexbox, and mobile-first approach.'],
                ['question' => 'How does HTTP work between browser and server?', 'tip' => 'Request/response, status codes (200, 404, 500).'],
                ['question' => 'What is Laravel MVC?', 'tip' => 'Relate to this Arivexa project if you built it!'],
                ['question' => 'How do you improve website performance?', 'tip' => 'Caching, image optimization, minify CSS/JS.'],
            ],
            'design' => [
                ['question' => 'Walk us through your design process.', 'tip' => 'Research → wireframe → prototype → test → iterate.'],
                ['question' => 'What is the difference between UI and UX?', 'tip' => 'UI = look; UX = overall experience and usability.'],
                ['question' => 'How do you handle client feedback you disagree with?', 'tip' => 'Show professionalism — explain rationale with user data.'],
                ['question' => 'What tools do you use for design?', 'tip' => 'Figma, Adobe XD — show a portfolio piece.'],
                ['question' => 'Explain accessibility in design.', 'tip' => 'Colour contrast, font size, screen reader friendly labels.'],
            ],
            'cybersecurity' => [
                ['question' => 'Explain the CIA triad.', 'tip' => 'Confidentiality, Integrity, Availability with examples.'],
                ['question' => 'What is phishing and how do users protect themselves?', 'tip' => 'Mention email verification and MFA.'],
                ['question' => 'Difference between symmetric and asymmetric encryption?', 'tip' => 'Same key vs public/private key pair.'],
                ['question' => 'How would you respond to a suspected security breach?', 'tip' => 'Isolate, log, notify, investigate, patch.'],
                ['question' => 'What certifications are you pursuing?', 'tip' => 'CompTIA Security+, CEH, or networking fundamentals.'],
            ],
            'mobile-development' => [
                ['question' => 'Native vs cross-platform development — pros and cons?', 'tip' => 'Kotlin/Swift vs Flutter/React Native.'],
                ['question' => 'How do you manage app state?', 'tip' => 'Mention ViewModel, Provider, or Riverpod patterns.'],
                ['question' => 'Describe an app you built or contributed to.', 'tip' => 'Screenshots, Play Store link, or APK demo.'],
                ['question' => 'How do you test a mobile app?', 'tip' => 'Unit tests, emulator, real device testing.'],
                ['question' => 'Explain REST API integration in mobile apps.', 'tip' => 'JSON parsing, error handling, loading states.'],
            ],
            'digital-marketing' => [
                ['question' => 'How do you measure campaign success?', 'tip' => 'KPIs: CTR, conversion rate, ROAS, engagement.'],
                ['question' => 'Explain SEO basics.', 'tip' => 'Keywords, meta tags, backlinks, page speed.'],
                ['question' => 'Difference between organic and paid social media?', 'tip' => 'Organic = free posts; paid = ads with targeting.'],
                ['question' => 'Describe a campaign you ran or planned.', 'tip' => 'Objective, audience, channel, budget, results.'],
                ['question' => 'What tools do you use for analytics?', 'tip' => 'Google Analytics, Meta Business Suite, Google Ads.'],
            ],
            'cloud-devops' => [
                ['question' => 'What is CI/CD?', 'tip' => 'Continuous Integration / Deployment — automate build and deploy.'],
                ['question' => 'Explain Docker vs virtual machines.', 'tip' => 'Containers share OS kernel; VMs are heavier.'],
                ['question' => 'How do you monitor a production server?', 'tip' => 'Logs, alerts, uptime checks, Grafana/Prometheus.'],
                ['question' => 'What is Infrastructure as Code?', 'tip' => 'Terraform/CloudFormation — define servers in config files.'],
                ['question' => 'Describe a deployment you managed.', 'tip' => 'Git push → pipeline → staging → production.'],
            ],
            'ai-engineering' => [
                ['question' => 'Explain how a neural network learns.', 'tip' => 'Forward pass, loss function, backpropagation, weights update.'],
                ['question' => 'Difference between AI, ML, and deep learning?', 'tip' => 'Nested concepts — AI ⊃ ML ⊃ DL.'],
                ['question' => 'What is a transformer model?', 'tip' => 'Attention mechanism — basis of GPT and modern LLMs.'],
                ['question' => 'How do you evaluate an ML model?', 'tip' => 'Accuracy, F1, confusion matrix — pick metric for problem type.'],
                ['question' => 'Describe an ML project from your portfolio.', 'tip' => 'Dataset, model choice, results, what you learned.'],
            ],
            default => [
                ['question' => 'Tell us about yourself and your career goals.', 'tip' => 'Keep it 2 minutes — education, skills, why this field.'],
                ['question' => 'Why do you want this role?', 'tip' => 'Connect your Arivexa career match to the job.'],
                ['question' => 'What are your strengths and weaknesses?', 'tip' => 'Honest weakness + how you are improving it.'],
            ],
        };
    }

    /**
     * @return array<int, array{title: string, url: string, type: string}>
     */
    public static function resources(string $slug): array
    {
        return match ($slug) {
            'programming' => [
                ['title' => 'Java Tutorial (W3Schools)', 'url' => 'https://www.w3schools.com/java/', 'type' => 'Tutorial'],
                ['title' => 'freeCodeCamp — Java Full Course', 'url' => 'https://www.youtube.com/freecodecamp', 'type' => 'Video'],
                ['title' => 'LeetCode Practice', 'url' => 'https://leetcode.com/', 'type' => 'Practice'],
                ['title' => 'GitHub — Open Source', 'url' => 'https://github.com/', 'type' => 'Portfolio'],
            ],
            'data-science' => [
                ['title' => 'Kaggle Learn', 'url' => 'https://www.kaggle.com/learn', 'type' => 'Course'],
                ['title' => 'Python Data Science Handbook', 'url' => 'https://jakevdp.github.io/PythonDataScienceHandbook/', 'type' => 'Book'],
                ['title' => 'Google Colab', 'url' => 'https://colab.research.google.com/', 'type' => 'Tool'],
                ['title' => 'Pandas Documentation', 'url' => 'https://pandas.pydata.org/docs/', 'type' => 'Docs'],
            ],
            'web-development' => [
                ['title' => 'MDN Web Docs', 'url' => 'https://developer.mozilla.org/', 'type' => 'Docs'],
                ['title' => 'Laravel Documentation', 'url' => 'https://laravel.com/docs', 'type' => 'Docs'],
                ['title' => 'Frontend Mentor', 'url' => 'https://www.frontendmentor.io/', 'type' => 'Practice'],
                ['title' => 'CSS-Tricks', 'url' => 'https://css-tricks.com/', 'type' => 'Tutorial'],
            ],
            'design' => [
                ['title' => 'Figma Learn', 'url' => 'https://www.figma.com/resource-library/', 'type' => 'Course'],
                ['title' => 'Behance Portfolio', 'url' => 'https://www.behance.net/', 'type' => 'Portfolio'],
                ['title' => 'Laws of UX', 'url' => 'https://lawsofux.com/', 'type' => 'Guide'],
                ['title' => 'Dribbble Inspiration', 'url' => 'https://dribbble.com/', 'type' => 'Inspiration'],
            ],
            'cybersecurity' => [
                ['title' => 'TryHackMe', 'url' => 'https://tryhackme.com/', 'type' => 'Labs'],
                ['title' => 'OWASP Top 10', 'url' => 'https://owasp.org/www-project-top-ten/', 'type' => 'Guide'],
                ['title' => 'Cybrary Free Courses', 'url' => 'https://www.cybrary.it/', 'type' => 'Course'],
                ['title' => 'HackTheBox', 'url' => 'https://www.hackthebox.com/', 'type' => 'Practice'],
            ],
            'mobile-development' => [
                ['title' => 'Android Developers', 'url' => 'https://developer.android.com/courses', 'type' => 'Course'],
                ['title' => 'Flutter Docs', 'url' => 'https://docs.flutter.dev/', 'type' => 'Docs'],
                ['title' => 'React Native Docs', 'url' => 'https://reactnative.dev/docs/getting-started', 'type' => 'Docs'],
                ['title' => 'Expo Tutorial', 'url' => 'https://docs.expo.dev/tutorial/introduction/', 'type' => 'Tutorial'],
            ],
            'digital-marketing' => [
                ['title' => 'Google Digital Garage', 'url' => 'https://learndigital.withgoogle.com/', 'type' => 'Course'],
                ['title' => 'HubSpot Academy', 'url' => 'https://academy.hubspot.com/', 'type' => 'Course'],
                ['title' => 'Meta Blueprint', 'url' => 'https://www.facebook.com/business/learn', 'type' => 'Certification'],
                ['title' => 'Google Analytics Academy', 'url' => 'https://analytics.google.com/analytics/academy/', 'type' => 'Certification'],
            ],
            'cloud-devops' => [
                ['title' => 'AWS Skill Builder', 'url' => 'https://skillbuilder.aws/', 'type' => 'Course'],
                ['title' => 'Docker Getting Started', 'url' => 'https://docs.docker.com/get-started/', 'type' => 'Tutorial'],
                ['title' => 'Kubernetes Basics', 'url' => 'https://kubernetes.io/docs/tutorials/', 'type' => 'Docs'],
                ['title' => 'GitHub Actions Docs', 'url' => 'https://docs.github.com/en/actions', 'type' => 'Docs'],
            ],
            'ai-engineering' => [
                ['title' => 'Google AI Studio', 'url' => 'https://aistudio.google.com/', 'type' => 'Tool'],
                ['title' => 'Fast.ai Course', 'url' => 'https://course.fast.ai/', 'type' => 'Course'],
                ['title' => 'Hugging Face Learn', 'url' => 'https://huggingface.co/learn', 'type' => 'Course'],
                ['title' => 'Scikit-learn Docs', 'url' => 'https://scikit-learn.org/stable/user_guide.html', 'type' => 'Docs'],
            ],
            default => [
                ['title' => 'Arivexa Roadmap', 'url' => '/roadmap', 'type' => 'Internal'],
            ],
        };
    }
}
