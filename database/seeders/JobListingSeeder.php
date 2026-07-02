<?php

namespace Database\Seeders;

use App\Models\CareerDomain;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Database\Seeder;

class JobListingSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::where('is_admin', true)->value('id');

        $domain = fn (string $slug): ?int => CareerDomain::where('slug', $slug)->value('id');

        $jobs = [
            [
                'career_domain_id' => $domain('data-science'),
                'title' => 'Data Engineer',
                'company' => 'PacificKode',
                'location' => 'Colombo',
                'apply_url' => 'https://itpro.lk/job/13630/data-engineer-at-pacifickode/',
                'apply_note' => 'Source: ITPro.lk — view full details and apply on the job page.',
                'expires_at' => '2026-07-31',
            ],
            [
                'career_domain_id' => $domain('web-development'),
                'title' => 'WordPress Developer',
                'company' => 'Well Treasure Asia',
                'location' => 'Colombo',
                'apply_url' => 'https://itpro.lk/job/14170/wordpress-developer-at-well-treasure-asia/',
                'apply_note' => 'Source: ITPro.lk',
                'expires_at' => '2026-07-31',
            ],
            [
                'career_domain_id' => $domain('mobile-development'),
                'title' => 'Mobile Developer - Android',
                'company' => 'DirectFN',
                'location' => 'Colombo',
                'apply_url' => 'https://itpro.lk/job/14167/mobile-developer-android-at-directfn/',
                'apply_note' => 'Source: ITPro.lk',
                'expires_at' => '2026-07-31',
            ],
            [
                'career_domain_id' => $domain('cybersecurity'),
                'title' => 'Senior Cybersecurity Analyst',
                'company' => 'WIA Systems Inc',
                'location' => 'Colombo',
                'apply_url' => 'https://itpro.lk/job/14149/senior-cybersecurity-analyst-at-wia-systems-inc/',
                'apply_note' => 'Source: ITPro.lk',
                'expires_at' => '2026-07-31',
            ],
            [
                'career_domain_id' => $domain('ai-engineering'),
                'title' => 'Product Engineer - AI & Automation (R&D)',
                'company' => 'SFL Tech',
                'location' => 'Colombo',
                'apply_url' => 'https://itpro.lk/job/14138/product-engineer-ai-automation-rd-at-sfl-tech/',
                'apply_note' => 'Source: ITPro.lk',
                'expires_at' => '2026-07-31',
            ],
            [
                'career_domain_id' => $domain('digital-marketing'),
                'title' => 'Digital Marketer',
                'company' => 'Digit Web Lanka (Pvt) Ltd',
                'location' => 'Jaffna',
                'apply_url' => 'https://itpro.lk/job/14178/digital-marketer-at-digit-web-lanka-pvt-ltd/',
                'apply_note' => 'Source: ITPro.lk',
                'expires_at' => '2026-07-31',
            ],
            [
                'career_domain_id' => $domain('web-development'),
                'title' => 'Full Stack Developer Trainee Internship',
                'company' => 'ITX Digital Services (Pvt) Ltd',
                'location' => 'Colombo',
                'apply_url' => 'https://itpro.lk/job/14152/full-stack-developer-trainee-internship-at-itx-digital-services-pvt-ltd/',
                'apply_note' => 'Source: ITPro.lk',
                'expires_at' => '2026-07-31',
            ],
            [
                'career_domain_id' => $domain('programming'),
                'title' => 'Intern / Junior Software Engineer',
                'company' => 'Vision Tech Business Solutions (Pvt) Ltd',
                'location' => 'Kadawatha',
                'apply_url' => 'https://itpro.lk/job/14137/intern-junior-software-engineer-at-vision-tech-business-solutions-pvt-ltd/',
                'apply_note' => 'Source: ITPro.lk',
                'expires_at' => '2026-07-31',
            ],
            [
                'career_domain_id' => $domain('design'),
                'title' => 'Graphic Designer',
                'company' => 'Digitech Holdings (Pvt) Ltd',
                'location' => 'Malabe',
                'apply_url' => 'https://itpro.lk/job/14157/graphic-designer-at-digitech-holdings-pvt-ltd/',
                'apply_note' => 'Source: ITPro.lk',
                'expires_at' => '2026-07-31',
            ],
            [
                'career_domain_id' => $domain('cloud-devops'),
                'title' => 'Systems & Network Engineer',
                'company' => 'Digitech Holdings (Pvt) Ltd',
                'location' => 'Malabe',
                'apply_url' => 'https://itpro.lk/job/14160/systems-network-engineer-at-digitech-holdings-pvt-ltd/',
                'apply_note' => 'Source: ITPro.lk',
                'expires_at' => '2026-07-31',
            ],
            [
                'career_domain_id' => $domain('programming'),
                'title' => 'Senior Developer / Tech Lead (Angular & .Net)',
                'company' => 'BISTEC Global Services',
                'location' => 'Colombo',
                'apply_url' => 'https://itpro.lk/job/13699/senior-developer-tech-lead-senior-tech-lead-full-stack-development-angular-net-at-bistec-global-services/',
                'apply_note' => 'Source: ITPro.lk',
                'expires_at' => '2026-07-31',
            ],
            [
                'career_domain_id' => $domain('programming'),
                'title' => 'Integration Engineer',
                'company' => 'Ifinity Global',
                'location' => 'Colombo',
                'apply_url' => 'https://itpro.lk/job/14173/integration-engineer-at-ifinity-global/',
                'apply_note' => 'Source: ITPro.lk',
                'expires_at' => '2026-07-31',
            ],
        ];

        foreach ($jobs as $job) {
            JobListing::updateOrCreate(
                [
                    'title' => $job['title'],
                    'company' => $job['company'],
                ],
                [
                    ...$job,
                    'is_active' => true,
                    'created_by' => $adminId,
                ]
            );
        }
    }
}
