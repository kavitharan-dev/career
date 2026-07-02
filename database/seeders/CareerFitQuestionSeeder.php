<?php

namespace Database\Seeders;

use App\Models\CareerDomain;
use App\Models\CareerFitQuestion;
use Illuminate\Database\Seeder;

class CareerFitQuestionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (CareerFitQuestionData::all() as $slug => $questions) {
            $domain = CareerDomain::where('slug', $slug)->first();

            if (! $domain) {
                continue;
            }

            foreach ($questions as $index => $q) {
                CareerFitQuestion::updateOrCreate(
                    [
                        'career_domain_id' => $domain->id,
                        'question_key' => $q['key'],
                    ],
                    [
                        'question_text' => $q['text'],
                        'options' => $q['options'],
                        'sort_order' => $index + 1,
                    ]
                );
            }
        }
    }
}
