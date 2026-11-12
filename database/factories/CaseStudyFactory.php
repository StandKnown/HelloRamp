<?php

namespace Database\Factories;

use App\Models\CaseStudy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<CaseStudy> */
class CaseStudyFactory extends Factory
{
    protected $model = CaseStudy::class;

    public function definition(): array
    {
        $name = fake()->company().' Auto';

        return [
            'slug' => Str::slug($name).'-'.Str::random(4),
            'dealership_name' => $name,
            'location' => fake()->city().', '.fake()->stateAbbr(),
            'headline' => 'How '.$name.' grew online leads with HelloRamp',
            'subheadline' => 'Consistent, premium vehicle photography at scale.',
            'quote' => fake()->paragraph(2),
            'quote_author' => fake()->name(),
            'quote_author_role' => 'Owner',
            'body' => fake()->paragraphs(4, true),
            'metric_label' => 'More online leads',
            'metric_value' => fake()->numberBetween(15, 40).'%',
            'is_featured' => fake()->boolean(30),
            'is_published' => true,
            'published_at' => now()->subDays(fake()->numberBetween(1, 180)),
        ];
    }
}
