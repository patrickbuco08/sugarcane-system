<?php

namespace Database\Seeders;

use Bocum\Models\WeeklyPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class WeeklyPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create weekly prices for the last 12 weeks
        $startDate = Carbon::now()->subWeeks(12)->startOfWeek(); // Start from 12 weeks ago, Monday
        
        for ($i = 0; $i < 12; $i++) {
            $weekOf = $startDate->copy()->addWeeks($i);
            
            // Skip if this week already exists
            if (WeeklyPrice::where('week_of', $weekOf->format('Y-m-d'))->exists()) {
                continue;
            }
            
            WeeklyPrice::create([
                'week_of' => $weekOf->format('Y-m-d'),
                'b_domestic' => fake()->randomFloat(2, 2200, 2800), // ₱2200-2800/LKG
                'a_us_quota' => fake()->optional(0.8)->randomFloat(2, 2600, 3200), // Optional
                'molasses_mt' => fake()->optional(0.7)->randomFloat(2, 1100, 1400), // Optional
                'source' => fake()->randomElement([
                    'SRA Weekly Millsite Prices',
                    'Sugar Regulatory Administration',
                    'Philippine Sugar Millers Association',
                    null, // Some entries might not have a source
                ]),
            ]);
        }
        
        // Create a few specific recent weeks with realistic data
        $recentWeeks = [
            [
                'week_of' => Carbon::now()->startOfWeek()->format('Y-m-d'), // This week
                'b_domestic' => 2650.00,
                'a_us_quota' => 2950.00,
                'molasses_mt' => 1250.00,
                'source' => 'SRA Weekly Millsite Prices',
            ],
            [
                'week_of' => Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d'), // Last week
                'b_domestic' => 2625.50,
                'a_us_quota' => 2925.75,
                'molasses_mt' => 1225.50,
                'source' => 'SRA Weekly Millsite Prices',
            ],
            [
                'week_of' => Carbon::now()->subWeeks(2)->startOfWeek()->format('Y-m-d'), // 2 weeks ago
                'b_domestic' => 2600.25,
                'a_us_quota' => null, // Sometimes A US Quota is not available
                'molasses_mt' => 1200.00,
                'source' => 'Sugar Regulatory Administration',
            ],
        ];
        
        foreach ($recentWeeks as $weekData) {
            WeeklyPrice::updateOrCreate(
                ['week_of' => $weekData['week_of']],
                $weekData
            );
        }
        
        $this->command->info('Created weekly price data for the last 12 weeks');
    }
}
