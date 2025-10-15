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
        $recentWeeks = [
            [
                'week_of' => '2025-08-03',
                'b_domestic' => 2500,
                'a_us_quota' => null,
                'molasses_mt' => null,
                'source' => 'SRA Weekly Millsite Prices',
            ],
            [
                'week_of' => '2025-08-10',
                'b_domestic' => 2500,
                'a_us_quota' => null,
                'molasses_mt' => null,
                'source' => 'SRA Weekly Millsite Prices',
            ],
            [
                'week_of' => '2025-08-17',
                'b_domestic' => 2600.25,
                'a_us_quota' => null,
                'molasses_mt' => null,
                'source' => 'Sugar Regulatory Administration',
            ],
            [
                'week_of' => '2025-08-24',
                'b_domestic' => 2350,
                'a_us_quota' => null,
                'molasses_mt' => null,
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
