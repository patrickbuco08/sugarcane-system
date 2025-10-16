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
        // Sample data from sugarcane_dataset.csv
        // Samples 1-9: harvest_batch_id = 1
        // Samples 10-12: harvest_batch_id = 2
        // Samples 13-30: harvest_batch_id = 3
        $sampleData = [
            ['label' => 'AB1 - 105', 'harvest_batch_id' => 1, 'avg_brix' => 18.86, 'pol' => 17.69, 'ch_r' => 1385.51, 'ch_s' => 339.31, 'ch_t' => 193.41, 'ch_u' => 119.00, 'ch_v' => 82.75, 'ch_w' => 43.06],
            ['label' => 'AB2 - 105', 'harvest_batch_id' => 1, 'avg_brix' => 14.96, 'pol' => 11.59, 'ch_r' => 1157.00, 'ch_s' => 274.26, 'ch_t' => 162.42, 'ch_u' => 99.36, 'ch_v' => 69.18, 'ch_w' => 35.89],
            ['label' => 'AB3 - 105', 'harvest_batch_id' => 1, 'avg_brix' => 16.56, 'pol' => 14.59, 'ch_r' => 2377.54, 'ch_s' => 532.26, 'ch_t' => 326.98, 'ch_u' => 210.61, 'ch_v' => 148.39, 'ch_w' => 74.40],
            ['label' => 'AT1 - 105', 'harvest_batch_id' => 1, 'avg_brix' => 17.9, 'pol' => 16.08, 'ch_r' => 1648.66, 'ch_s' => 431.23, 'ch_t' => 282.32, 'ch_u' => 172.27, 'ch_v' => 109.48, 'ch_w' => 52.63],
            ['label' => 'AT2 - 105', 'harvest_batch_id' => 1, 'avg_brix' => 15.97, 'pol' => 12.46, 'ch_r' => 1925.43, 'ch_s' => 534.22, 'ch_t' => 314.80, 'ch_u' => 198.90, 'ch_v' => 129.72, 'ch_w' => 64.59],
            ['label' => 'AT3 - 105', 'harvest_batch_id' => 1, 'avg_brix' => 13.47, 'pol' => 9.74, 'ch_r' => 4169.09, 'ch_s' => 1130.44, 'ch_t' => 584.72, 'ch_u' => 389.12, 'ch_v' => 268.09, 'ch_w' => 132.78],
            ['label' => 'BB1 - 105', 'harvest_batch_id' => 1, 'avg_brix' => 16.37, 'pol' => 14.44, 'ch_r' => 4610.56, 'ch_s' => 1096.62, 'ch_t' => 633.67, 'ch_u' => 421.23, 'ch_v' => 293.84, 'ch_w' => 145.70],
            ['label' => 'BB2 - 105', 'harvest_batch_id' => 1, 'avg_brix' => 16.57, 'pol' => 14.4, 'ch_r' => 3214.96, 'ch_s' => 744.74, 'ch_t' => 464.19, 'ch_u' => 309.21, 'ch_v' => 211.68, 'ch_w' => 105.27],
            ['label' => 'BB3 - 105', 'harvest_batch_id' => 1, 'avg_brix' => 15.87, 'pol' => 14.32, 'ch_r' => 6281.03, 'ch_s' => 1433.75, 'ch_t' => 853.79, 'ch_u' => 577.06, 'ch_v' => 400.56, 'ch_w' => 195.70],
            ['label' => 'BT1 - 105', 'harvest_batch_id' => 2, 'avg_brix' => 13.87, 'pol' => 9.2, 'ch_r' => 3456.29, 'ch_s' => 974.99, 'ch_t' => 474.02, 'ch_u' => 312.80, 'ch_v' => 218.56, 'ch_w' => 110.05],
            ['label' => 'BT2 - 105', 'harvest_batch_id' => 2, 'avg_brix' => 18.5, 'pol' => 16.87, 'ch_r' => 963.12, 'ch_s' => 249.33, 'ch_t' => 173.11, 'ch_u' => 106.72, 'ch_v' => 66.83, 'ch_w' => 33.49],
            ['label' => 'BT3 - 105', 'harvest_batch_id' => 2, 'avg_brix' => 12.57, 'pol' => 8.56, 'ch_r' => 1927.34, 'ch_s' => 499.09, 'ch_t' => 299.20, 'ch_u' => 191.35, 'ch_v' => 127.76, 'ch_w' => 63.40],
            ['label' => 'CT1 - 105', 'harvest_batch_id' => 2, 'avg_brix' => 14.57, 'pol' => 12.65, 'ch_r' => 1302.34, 'ch_s' => 277.73, 'ch_t' => 217.35, 'ch_u' => 134.68, 'ch_v' => 87.07, 'ch_w' => 43.06],
            ['label' => 'CT2 - 105', 'harvest_batch_id' => 2, 'avg_brix' => 15.57, 'pol' => 13.98, 'ch_r' => 1395.88, 'ch_s' => 296.81, 'ch_t' => 223.76, 'ch_u' => 138.65, 'ch_v' => 90.81, 'ch_w' => 45.46],
            ['label' => 'CT3 - 105', 'harvest_batch_id' => 2, 'avg_brix' => 16.47, 'pol' => 15.49, 'ch_r' => 904.50, 'ch_s' => 184.07, 'ch_t' => 176.31, 'ch_u' => 106.72, 'ch_v' => 64.86, 'ch_w' => 31.10],
            ['label' => 'CB1 - 105', 'harvest_batch_id' => 2, 'avg_brix' => 14.01, 'pol' => 10.14, 'ch_r' => 3212.51, 'ch_s' => 804.14, 'ch_t' => 549.89, 'ch_u' => 366.26, 'ch_v' => 231.93, 'ch_w' => 112.44],
            ['label' => 'CB2 - 105', 'harvest_batch_id' => 2, 'avg_brix' => 13.71, 'pol' => 9.32, 'ch_r' => 1463.23, 'ch_s' => 313.29, 'ch_t' => 265.86, 'ch_u' => 169.06, 'ch_v' => 105.15, 'ch_w' => 50.72],
            ['label' => 'CB3 - 105', 'harvest_batch_id' => 2, 'avg_brix' => 14.2, 'pol' => 8.75, 'ch_r' => 904.77, 'ch_s' => 227.65, 'ch_t' => 181.44, 'ch_u' => 111.45, 'ch_v' => 66.83, 'ch_w' => 32.30],
            ['label' => 'DT1 - 84-524', 'harvest_batch_id' => 3, 'avg_brix' => 8.4, 'pol' => 3.03, 'ch_r' => 1600.12, 'ch_s' => 465.27, 'ch_t' => 297.71, 'ch_u' => 194.18, 'ch_v' => 118.91, 'ch_w' => 58.61],
            ['label' => 'DT2 - 84-524', 'harvest_batch_id' => 3, 'avg_brix' => 8.8, 'pol' => 4.72, 'ch_r' => 4146.45, 'ch_s' => 1257.71, 'ch_t' => 684.10, 'ch_u' => 471.85, 'ch_v' => 299.74, 'ch_w' => 146.89],
            ['label' => 'DT3 - 84-524', 'harvest_batch_id' => 3, 'avg_brix' => 8.7, 'pol' => 3.03, 'ch_r' => 1995.51, 'ch_s' => 614.65, 'ch_t' => 390.03, 'ch_u' => 260.67, 'ch_v' => 156.26, 'ch_w' => 75.36],
            ['label' => 'DB1 - 84-524', 'harvest_batch_id' => 3, 'avg_brix' => 13.1, 'pol' => 7.82, 'ch_r' => 1387.97, 'ch_s' => 370.74, 'ch_t' => 272.49, 'ch_u' => 176.05, 'ch_v' => 106.53, 'ch_w' => 51.68],
            ['label' => 'DB2 - 84-524', 'harvest_batch_id' => 3, 'avg_brix' => 13.0, 'pol' => 7.45, 'ch_r' => 1672.65, 'ch_s' => 363.80, 'ch_t' => 259.66, 'ch_u' => 159.61, 'ch_v' => 106.14, 'ch_w' => 52.63],
            ['label' => 'DB3 - 84-524', 'harvest_batch_id' => 3, 'avg_brix' => 12.8, 'pol' => 12.26, 'ch_r' => 2096.40, 'ch_s' => 475.46, 'ch_t' => 359.47, 'ch_u' => 231.20, 'ch_v' => 147.02, 'ch_w' => 70.58],
            ['label' => 'ET1 - 2289', 'harvest_batch_id' => 3, 'avg_brix' => 15.21, 'pol' => 12.95, 'ch_r' => 1228.99, 'ch_s' => 272.96, 'ch_t' => 205.81, 'ch_u' => 126.56, 'ch_v' => 82.55, 'ch_w' => 40.67],
            ['label' => 'ET2 - 2289', 'harvest_batch_id' => 3, 'avg_brix' => 15.91, 'pol' => 13.29, 'ch_r' => 2673.13, 'ch_s' => 618.99, 'ch_t' => 458.85, 'ch_u' => 309.03, 'ch_v' => 196.35, 'ch_w' => 95.70],
            ['label' => 'ET3 - 2289', 'harvest_batch_id' => 3, 'avg_brix' => 16.32, 'pol' => 13.88, 'ch_r' => 1125.64, 'ch_s' => 243.91, 'ch_t' => 194.91, 'ch_u' => 121.65, 'ch_v' => 77.64, 'ch_w' => 38.28],
            ['label' => 'EB1 - 2289', 'harvest_batch_id' => 3, 'avg_brix' => 16.32, 'pol' => 13.51, 'ch_r' => 2740.76, 'ch_s' => 643.70, 'ch_t' => 415.89, 'ch_u' => 264.07, 'ch_v' => 176.89, 'ch_w' => 87.32],
            ['label' => 'EB2 - 2289', 'harvest_batch_id' => 3, 'avg_brix' => 16.62, 'pol' => 14.2, 'ch_r' => 3469.10, 'ch_s' => 804.58, 'ch_t' => 545.19, 'ch_u' => 354.93, 'ch_v' => 232.91, 'ch_w' => 112.44],
            ['label' => 'EB3 - 2289', 'harvest_batch_id' => 3, 'avg_brix' => 17.12, 'pol' => 14.86, 'ch_r' => 945.95, 'ch_s' => 218.33, 'ch_t' => 171.61, 'ch_u' => 105.97, 'ch_v' => 65.84, 'ch_w' => 32.30],
        ];

        $samples = [];
        foreach ($sampleData as $data) {
            $samples[] = [
                'harvest_batch_id' => $data['harvest_batch_id'],
                'label'            => $data['label'],
                'avg_brix'         => $data['avg_brix'],
                'pol'              => $data['pol'],
                'ch_r'             => $data['ch_r'],
                'ch_s'             => $data['ch_s'],
                'ch_t'             => $data['ch_t'],
                'ch_u'             => $data['ch_u'],
                'ch_v'             => $data['ch_v'],
                'ch_w'             => $data['ch_w'],
                'model_version'    => 'lasso_v1_2025',
                'coeff_hash'       => md5(json_encode(['brix' => $data['avg_brix'], 'pol' => $data['pol']])),
                'created_at'       => now(),
                'updated_at'       => now(),
            ];
        }

        DB::table('samples')->insert($samples);
    }
}
