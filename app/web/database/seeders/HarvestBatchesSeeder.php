<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HarvestBatchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the weekly prices to reference their IDs
        $firstWeek = DB::table('weekly_prices')->where('week_of', '2025-09-01')->first();
        $secondWeek = DB::table('weekly_prices')->where('week_of', '2025-09-08')->first();
        
        DB::table('harvest_batches')->insert([
            [
                'label'           => 'Week 36 - Field A',
                'week_of'         => '2025-09-01',
                'weekly_price_id' => $firstWeek->id,
                'tons_harvested'  => 15.5,
                'recovery_coeff'  => 0.92,
                'farmers_share'   => 0.70,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'label'           => 'Week 37 - Field B',
                'week_of'         => '2025-09-08',
                'weekly_price_id' => $secondWeek->id,
                'tons_harvested'  => 18.2,
                'recovery_coeff'  => 0.91,
                'farmers_share'   => 0.71,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]
        ]);
    }
}
