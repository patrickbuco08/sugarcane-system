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
        $sampleData = [
            ['label' => 'Sample A1 - Morning', 'harvest_batch_id' => 1, 'avg_brix' => 17.2, 'pol' => 15.0],
            ['label' => 'Sample A2 - Afternoon', 'harvest_batch_id' => 1, 'avg_brix' => 17.8, 'pol' => 15.2],
            ['label' => 'Sample B1 - Morning', 'harvest_batch_id' => 2, 'avg_brix' => 18.0, 'pol' => 15.5],
            ['label' => 'Sample B2 - Afternoon', 'harvest_batch_id' => 2, 'avg_brix' => 18.4, 'pol' => 15.8],
            ['label' => 'Sample C1 - Morning', 'harvest_batch_id' => 3, 'avg_brix' => 18.6, 'pol' => 16.1],
            ['label' => 'Sample C2 - Afternoon', 'harvest_batch_id' => 3, 'avg_brix' => 17.9, 'pol' => 15.6],
        ];

        $samples = [];
        for ($i = 0; $i < 6; $i++) {
            $samples[] = [
                'harvest_batch_id' => $sampleData[$i]['harvest_batch_id'],
                'label'            => $sampleData[$i]['label'],
                'avg_brix'         => $sampleData[$i]['avg_brix'],
                'pol'              => $sampleData[$i]['pol'],
                'ch_r'             => rand(1000, 1300),
                'ch_s'             => rand(900, 1200),
                'ch_t'             => rand(800, 1100),
                'ch_u'             => rand(700, 1000),
                'ch_v'             => rand(600, 900),
                'ch_w'             => rand(500, 800),
                'model_version'    => 'brix_v1_2025_08',
                'coeff_hash'       => 'd' . substr(md5(rand()), 0, 5),
                'created_at'       => now(),
                'updated_at'       => now(),
            ];
        }

        DB::table('samples')->insert($samples);
    }
}
