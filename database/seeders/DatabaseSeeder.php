<?php

namespace Database\Seeders;

use App\Models\CaseStudy;
use App\Models\Dealership;
use App\Models\Photo;
use App\Models\Plan;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->seedPlans();
        $this->seedCaseStudies();

        $dealership = Dealership::factory()->create([
            'name' => 'Auto Web Expo',
            'slug' => 'auto-web-expo',
            'city' => 'Plano',
            'state' => 'TX',
        ]);

        $admin = User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@helloramp.test',
            'dealership_id' => $dealership->id,
        ]);

        $member = User::factory()->create([
            'name' => 'Ben Amini',
            'email' => 'owner@helloramp.test',
            'dealership_id' => $dealership->id,
        ]);

        Vehicle::factory()
            ->count(6)
            ->for($dealership)
            ->sequence(fn ($seq) => ['created_by' => $member->id])
            ->create()
            ->each(function (Vehicle $v) use ($member) {
                Photo::factory()
                    ->count(4)
                    ->ready()
                    ->for($v)
                    ->sequence(fn ($seq) => ['uploaded_by' => $member->id])
                    ->create();
            });
    }

    private function seedPlans(): void
    {
        $plans = [
            [
                'slug' => 'standard', 'name' => 'Standard', 'tagline' => 'For single-lot dealers getting started.',
                'price_cents' => 12000, 'vehicle_limit' => 50, 'sort_order' => 1,
                'features' => ['Studio-quality photos', 'Reflection cleanup', 'Up to 50 vehicles / month', 'Standard support'],
            ],
            [
                'slug' => 'pro', 'name' => 'Pro', 'tagline' => 'For growing dealerships.',
                'price_cents' => 18900, 'vehicle_limit' => 150, 'sort_order' => 2, 'is_featured' => true,
                'features' => ['Everything in Standard', 'Up to 150 vehicles / month', '360° spins', 'Priority support'],
            ],
            [
                'slug' => 'ultra', 'name' => 'Ultra', 'tagline' => 'For dealer groups & online platforms.',
                'price_cents' => 29900, 'vehicle_limit' => 400, 'sort_order' => 3,
                'features' => ['Everything in Pro', 'Up to 400 vehicles / month', 'AI Walkaround videos', 'Dedicated CS manager'],
            ],
        ];

        foreach ($plans as $p) {
            Plan::updateOrCreate(['slug' => $p['slug']], $p);
        }
    }

    private function seedCaseStudies(): void
    {
        $studies = [
            [
                'slug' => 'auto-web-expo',
                'dealership_name' => 'Auto Web Expo',
                'location' => 'Plano, TX',
                'headline' => '25% more online leads with cleaner photos',
                'subheadline' => 'How Auto Web Expo standardized every listing shot.',
                'quote' => 'Customers often tell us they found us online because the cars stood out in the photos.',
                'quote_author' => 'Ben Amini',
                'quote_author_role' => 'Owner, Auto Web Expo',
                'body' => "Auto Web Expo turned to HelloRamp to solve a familiar problem: lot photography was inconsistent, backgrounds were cluttered, and reflections on glossy paint gave away where the photos were really taken. Within weeks of switching, every vehicle on the lot was being photographed by phone and processed into consistent, high-trust listing imagery.",
                'metric_label' => 'More online leads',
                'metric_value' => '25%',
                'is_featured' => true,
            ],
            [
                'slug' => 'diamond-ii-auto',
                'dealership_name' => 'Diamond II Auto Sales',
                'location' => 'Orlando, FL',
                'headline' => '2.5× faster arrival-to-live',
                'subheadline' => 'From lot delivery to published online in minutes.',
                'quote' => 'Arrival-to-live used to take days. Now it is same-day. Support has been responsive every step of the way.',
                'quote_author' => 'Jassie',
                'quote_author_role' => 'Operations, Diamond II Auto Sales',
                'body' => 'Diamond II needed to move vehicles live faster without hiring a dedicated photo team. HelloRamp reduced their arrival-to-live time by 2.5× and gave them consistent, premium-feeling imagery across their entire inventory.',
                'metric_label' => 'Faster arrival-to-live',
                'metric_value' => '2.5×',
                'is_featured' => true,
            ],
        ];

        foreach ($studies as $s) {
            CaseStudy::updateOrCreate(
                ['slug' => $s['slug']],
                array_merge($s, ['is_published' => true, 'published_at' => now()->subDays(random_int(10, 180))]),
            );
        }
    }
}
