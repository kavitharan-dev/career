<?php

namespace Database\Seeders;

/**
 * Career guidance content shown to students (not learning tasks).
 */
class CareerDomainGuidanceData
{
    public static function forSlug(string $slug): array
    {
        return match ($slug) {
            'programming' => [
                'salary_range' => 'LKR 80,000 – 350,000+ / month (junior to senior)',
                'job_outlook' => 'High demand — software companies, banks, startups, remote jobs',
                'education_path' => 'O/L & A/L (Math), IT diploma/degree, online certs (Java, Spring)',
                'typical_roles' => ['Junior Developer', 'Backend Developer', 'Full-Stack Developer', 'Software Engineer'],
                'key_skills' => ['Programming', 'Problem solving', 'Git', 'Databases', 'OOP', 'APIs'],
                'day_in_life' => 'Write and test code, fix bugs, attend stand-up meetings, review pull requests, deploy features with the team.',
                'sri_lanka_context' => 'Growing IT parks in Colombo, Kandy, and remote work for foreign clients. Java and web stacks are common in local industry.',
                'who_should_choose' => 'You enjoy logic, building apps, and learning new technologies. Strong marks in Math/CS help but practice matters most.',
            ],
            'data-science' => [
                'salary_range' => 'LKR 100,000 – 400,000+ / month',
                'job_outlook' => 'Growing — analytics teams in telecom, banking, retail, and tech',
                'education_path' => 'Statistics/Math/CS degree, Python, SQL, ML courses',
                'typical_roles' => ['Data Analyst', 'Business Analyst', 'Data Scientist', 'ML Engineer'],
                'key_skills' => ['Python', 'SQL', 'Statistics', 'Data visualization', 'Machine learning basics'],
                'day_in_life' => 'Clean data, build reports and dashboards, test models, present insights to managers.',
                'sri_lanka_context' => 'Banks and telcos hire analysts; startups need data-driven product decisions.',
                'who_should_choose' => 'You like numbers, patterns, and explaining data stories. Interest in “Data” or research fits well.',
            ],
            'web-development' => [
                'salary_range' => 'LKR 70,000 – 300,000+ / month',
                'job_outlook' => 'Very high — every business needs websites and web apps',
                'education_path' => 'IT diploma/degree, HTML/CSS/JS, Laravel or Node, portfolio projects',
                'typical_roles' => ['Front-End Developer', 'Back-End Developer', 'Full-Stack Developer', 'WordPress/Laravel Developer'],
                'key_skills' => ['HTML', 'CSS', 'JavaScript', 'Responsive design', 'Laravel/PHP or React'],
                'day_in_life' => 'Design UI, implement pages, connect APIs, fix layout bugs, deploy to server.',
                'sri_lanka_context' => 'Agencies and product companies hire web devs; freelancing for local SMEs is common.',
                'who_should_choose' => 'You enjoy visible results (websites), creativity + logic, and steady project work.',
            ],
            'design' => [
                'salary_range' => 'LKR 60,000 – 250,000+ / month',
                'job_outlook' => 'Steady — agencies, product companies, freelance branding/UI',
                'education_path' => 'Design diploma, Figma, portfolio, UX fundamentals',
                'typical_roles' => ['UI Designer', 'UX Designer', 'Graphic Designer', 'Product Designer'],
                'key_skills' => ['Figma', 'Typography', 'Color theory', 'User research', 'Prototyping'],
                'day_in_life' => 'Sketch wireframes, design screens, test with users, hand off to developers.',
                'sri_lanka_context' => 'Digital agencies in Colombo; global freelance on Behance/Dribbble.',
                'who_should_choose' => 'You are creative, care about how things look and feel, and enjoy user-focused work.',
            ],
            'cybersecurity' => [
                'salary_range' => 'LKR 120,000 – 450,000+ / month',
                'job_outlook' => 'Rising fast — banks, government, MSSPs need security staff',
                'education_path' => 'CS/IT degree, Security+, networking, ethical hacking labs',
                'typical_roles' => ['SOC Analyst', 'Security Engineer', 'Penetration Tester', 'GRC Consultant'],
                'key_skills' => ['Networking', 'Linux', 'Risk assessment', 'Incident response', 'Ethical mindset'],
                'day_in_life' => 'Monitor alerts, investigate incidents, patch systems, document policies, test vulnerabilities in lab.',
                'sri_lanka_context' => 'Financial sector invests heavily in security; certified professionals are in short supply.',
                'who_should_choose' => 'You are detail-oriented, ethical, and curious about how systems can be attacked and protected.',
            ],
            'mobile-development' => [
                'salary_range' => 'LKR 90,000 – 320,000+ / month',
                'job_outlook' => 'Strong — apps for banking, delivery, tourism, and startups',
                'education_path' => 'IT foundation, Kotlin/Android or Flutter, app portfolio',
                'typical_roles' => ['Android Developer', 'iOS Developer', 'Flutter Developer', 'Mobile Engineer'],
                'key_skills' => ['Kotlin/Java or Dart', 'UI for mobile', 'APIs', 'Play Store deployment'],
                'day_in_life' => 'Build screens, integrate APIs, test on devices, fix crashes, release updates.',
                'sri_lanka_context' => 'Local apps (finance, logistics) and offshore mobile teams hire regularly.',
                'who_should_choose' => 'You like building apps people use daily on phones and enjoy UI + logic together.',
            ],
            'digital-marketing' => [
                'salary_range' => 'LKR 50,000 – 200,000+ / month',
                'job_outlook' => 'High — SMEs, e-commerce, tourism, and agencies always need marketers',
                'education_path' => 'Marketing/IT mix, Google/Meta certs, content + analytics practice',
                'typical_roles' => ['Digital Marketer', 'SEO Specialist', 'Social Media Manager', 'Performance Marketer'],
                'key_skills' => ['SEO', 'Content writing', 'Social media', 'Google Analytics', 'Campaign planning'],
                'day_in_life' => 'Plan campaigns, write posts, analyze traffic, run ads, report ROI to clients.',
                'sri_lanka_context' => 'Tourism, retail, and online sellers depend on Facebook/Instagram and Google ads.',
                'who_should_choose' => 'You enjoy communication, creativity, business, and measuring what works.',
            ],
            'cloud-devops' => [
                'salary_range' => 'LKR 150,000 – 500,000+ / month',
                'job_outlook' => 'Very high globally and growing locally for scaled systems',
                'education_path' => 'IT degree, Linux, AWS/Azure basics, Docker, CI/CD',
                'typical_roles' => ['DevOps Engineer', 'Cloud Engineer', 'SRE', 'Platform Engineer'],
                'key_skills' => ['Linux', 'Docker', 'CI/CD', 'Cloud services', 'Scripting', 'Monitoring'],
                'day_in_life' => 'Automate deployments, manage servers, fix outages, improve pipeline speed and reliability.',
                'sri_lanka_context' => 'Product companies moving to cloud need DevOps; remote roles pay well.',
                'who_should_choose' => 'You like systems, automation, and keeping applications running 24/7.',
            ],
            'ai-engineering' => [
                'salary_range' => 'LKR 150,000 – 600,000+ / month',
                'job_outlook' => 'Fastest growing — AI teams in tech, research, and global remote roles',
                'education_path' => 'Strong Math/CS, Python, ML/DL courses, projects with real datasets',
                'typical_roles' => ['ML Engineer', 'AI Engineer', 'NLP Engineer', 'MLOps Engineer'],
                'key_skills' => ['Python', 'Mathematics', 'Machine learning', 'Deep learning', 'Model deployment'],
                'day_in_life' => 'Prepare data, train and evaluate models, deploy APIs, monitor model quality, research new techniques.',
                'sri_lanka_context' => 'Startups and offshore AI teams hire; global demand for ML skills is very high.',
                'who_should_choose' => 'You love math, programming, data, and building intelligent features — not just using ChatGPT.',
            ],
            default => [
                'salary_range' => 'Varies by experience and company',
                'job_outlook' => 'Moderate to high depending on specialization',
                'education_path' => 'Relevant diploma or degree plus online learning',
                'typical_roles' => ['Specialist', 'Consultant', 'Team lead'],
                'key_skills' => ['Communication', 'Problem solving', 'Domain knowledge'],
                'day_in_life' => 'Varies by role — mix of analysis, collaboration, and delivery.',
                'sri_lanka_context' => 'Opportunities in Colombo and remote work for international clients.',
                'who_should_choose' => 'Matches your interests, skills, and assessment scores.',
                'sri_lanka_jobs' => [],
            ],
        };
    }

    /**
     * @return array<int, array{title: string, employers: string, location: string, how_to_apply: string}>
     */
    public static function sriLankaJobs(string $slug): array
    {
        return match ($slug) {
            'programming' => [
                ['title' => 'Junior Java Developer', 'employers' => 'IFS, Sysco LABS, Virtusa', 'location' => 'Colombo / hybrid', 'how_to_apply' => 'Company careers pages, LinkedIn, topjobs.lk'],
                ['title' => 'Software Engineer (Graduate)', 'employers' => 'WSO2, 99X, hSenid', 'location' => 'Colombo, Malabe', 'how_to_apply' => 'Graduate intake + technical test'],
                ['title' => 'Backend Developer (PHP/Laravel)', 'employers' => 'Local agencies & product startups', 'location' => 'Colombo, remote', 'how_to_apply' => 'Portfolio on GitHub + interviews'],
            ],
            'data-science' => [
                ['title' => 'Data Analyst', 'employers' => 'Banks (Commercial, HNB), Dialog, Mobitel', 'location' => 'Colombo', 'how_to_apply' => 'LinkedIn, bank graduate programs'],
                ['title' => 'Business Intelligence Analyst', 'employers' => 'Retail chains, insurance, telecom', 'location' => 'Colombo', 'how_to_apply' => 'SQL + Power BI portfolio'],
                ['title' => 'Junior Data Scientist', 'employers' => 'Tech product companies, offshore teams', 'location' => 'Colombo / remote', 'how_to_apply' => 'Kaggle/GitHub projects + Python test'],
            ],
            'web-development' => [
                ['title' => 'Front-End Developer', 'employers' => 'Digital agencies, e-commerce', 'location' => 'Colombo', 'how_to_apply' => 'Live portfolio site'],
                ['title' => 'Laravel / PHP Developer', 'employers' => 'SME software houses', 'location' => 'Island-wide + remote', 'how_to_apply' => 'topjobs.lk, Facebook dev groups'],
                ['title' => 'Full-Stack Web Developer', 'employers' => 'Startups, offshore clients', 'location' => 'Remote-friendly', 'how_to_apply' => 'Freelance platforms + referrals'],
            ],
            'design' => [
                ['title' => 'UI/UX Designer', 'employers' => 'Creative agencies, product companies', 'location' => 'Colombo', 'how_to_apply' => 'Behance/Dribbble portfolio'],
                ['title' => 'Graphic Designer', 'employers' => 'Branding studios, media', 'location' => 'Colombo, Kandy', 'how_to_apply' => 'Portfolio PDF + interview'],
                ['title' => 'Product Designer', 'employers' => 'Tech startups (PickMe, Kapruka ecosystem)', 'location' => 'Hybrid', 'how_to_apply' => 'Case study presentation'],
            ],
            'cybersecurity' => [
                ['title' => 'SOC Analyst', 'employers' => 'Banks, MSSPs, telcos', 'location' => 'Colombo', 'how_to_apply' => 'Security+ / networking fundamentals'],
                ['title' => 'Information Security Officer', 'employers' => 'Finance, government contractors', 'location' => 'Colombo', 'how_to_apply' => 'Degree + security certifications'],
                ['title' => 'Penetration Tester (Junior)', 'employers' => 'Specialist security firms', 'location' => 'Colombo / remote', 'how_to_apply' => 'CTF profile, ethical hacking labs'],
            ],
            'mobile-development' => [
                ['title' => 'Android Developer (Kotlin)', 'employers' => 'FinTech, logistics apps', 'location' => 'Colombo', 'how_to_apply' => 'Play Store app demo'],
                ['title' => 'Flutter Developer', 'employers' => 'Startups, offshore', 'location' => 'Remote', 'how_to_apply' => 'Published app or APK sample'],
                ['title' => 'Mobile Engineer', 'employers' => 'Banks (mobile banking teams)', 'location' => 'Colombo', 'how_to_apply' => 'Graduate tech programs'],
            ],
            'digital-marketing' => [
                ['title' => 'Digital Marketing Executive', 'employers' => 'Hotels, tourism, retail', 'location' => 'Colombo, coastal cities', 'how_to_apply' => 'Meta/Google cert + sample campaigns'],
                ['title' => 'SEO Specialist', 'employers' => 'Agencies, e-commerce', 'location' => 'Remote possible', 'how_to_apply' => 'Case study: ranked keywords'],
                ['title' => 'Social Media Manager', 'employers' => 'SMEs, influencers, brands', 'location' => 'Island-wide', 'how_to_apply' => 'Managed page analytics screenshots'],
            ],
            'cloud-devops' => [
                ['title' => 'DevOps Engineer', 'employers' => 'Product companies, 99X, offshore', 'location' => 'Colombo / remote', 'how_to_apply' => 'Docker + CI/CD home lab'],
                ['title' => 'Cloud Support Engineer', 'employers' => 'AWS partners, MSPs', 'location' => 'Colombo', 'how_to_apply' => 'AWS Cloud Practitioner path'],
                ['title' => 'Site Reliability Engineer (Junior)', 'employers' => 'Scale-ups with global clients', 'location' => 'Remote', 'how_to_apply' => 'Linux + monitoring project'],
            ],
            'ai-engineering' => [
                ['title' => 'ML Engineer', 'employers' => 'AI startups, research labs, offshore', 'location' => 'Colombo / remote', 'how_to_apply' => 'GitHub ML projects + math test'],
                ['title' => 'AI Engineer (NLP/CV)', 'employers' => 'Tech product teams', 'location' => 'Hybrid', 'how_to_apply' => 'Thesis/internship + model demo'],
                ['title' => 'Data Scientist (ML focus)', 'employers' => 'Banks, telecom analytics', 'location' => 'Colombo', 'how_to_apply' => 'Competitions + Python stack'],
            ],
            default => [],
        };
    }
}
