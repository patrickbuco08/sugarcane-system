<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SamplesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $samples = [
            [
                'harvest_batch_id' => 1,
                'label'            => 'Morning sample',
                'avg_brix'         => 18.4,
                'pol'              => 15.2,
                'ch_r'             => 1234,
                'ch_s'             => 1110,
                'ch_t'             => 980,
                'ch_u'             => 875,
                'ch_v'             => 812,
                'ch_w'             => 760,
                'model_version'    => 'brix_v1_2025_08',
                'coeff_hash'       => 'c0a1f3',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]
        ];

        // Generate 10 more samples with null harvest_batch_id
        $labels = [
            'Field A Sample', 'Field B Sample', 'Field C Sample', 'Morning Harvest', 
            'Afternoon Sample', 'Evening Test', 'Quality Check', 'Random Sample',
            'Test Batch', 'Final Check'
        ];

        for ($i = 0; $i < 10; $i++) {
            $brix = rand(150, 220) / 10; // Random brix between 15.0 and 22.0
            $samples[] = [
                'harvest_batch_id' => null,
                'label'            => $labels[$i],
                'avg_brix'         => $brix,
                'pol'              => round($brix * 0.83, 2), // Rough estimate of pol from brix
                'ch_r'             => rand(1000, 1300),
                'ch_s'             => rand(900, 1200),
                'ch_t'             => rand(800, 1100),
                'ch_u'             => rand(700, 1000),
                'ch_v'             => rand(600, 900),
                'ch_w'             => rand(500, 800),
                'model_version'    => 'brix_v1_2025_08',
                'coeff_hash'       => 'd' . substr(md5(rand()), 0, 5),
                'created_at'       => now()->subDays(rand(1, 30)),
                'updated_at'       => now()->subDays(rand(0, 29)),
            ];
        }

        DB::table('samples')->insert($samples);
    }
}
