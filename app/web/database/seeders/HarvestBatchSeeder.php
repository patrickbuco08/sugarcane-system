<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HarvestBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        DB::table('harvest_batches')->insert([
            [
                'label'           => 'Batch A - Week 1',
                'week_of'         => '2025-08-03',
                'weekly_price_id' => 1,
                'tons_harvested'  => 10,
                'recovery_coeff'  => 0.9,
                'farmers_share'   => 0.69,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'label'           => 'Batch B - Week 2',
                'week_of'         => '2025-08-10',
                'weekly_price_id' => 2,
                'tons_harvested'  => 8,
                'recovery_coeff'  => 0.9,
                'farmers_share'   => 0.69,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'label'           => 'Batch C - Week 3',
                'week_of'         => '2025-08-17',
                'weekly_price_id' => 3,
                'tons_harvested'  => 12,
                'recovery_coeff'  => 0.9,
                'farmers_share'   => 0.69,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}
