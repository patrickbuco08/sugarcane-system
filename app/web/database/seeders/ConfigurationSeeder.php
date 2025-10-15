<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Bocum\Models\Configuration;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create configuration entries
        $configurations = [
            [
                'key' => 'override_predictions',
                'value' => '0',
            ],
            [
                'key' => 'brix',
                'value' => '0',
            ],
            [
                'key' => 'pol',
                'value' => '0',
            ],
            [
                'key' => 'brix_coefficients',
                'value' => [
                    'intercept' => 6.951527478871237,
                    'coefficients' => [
                        -0.09611712137799386,
                        -0.02296657687751865,
                        0.3015195623218867,
                        -0.18611109466309955,
                        -0.05010420703360781,
                        0.07415874480528968
                    ]
                ],
            ],
            [
                'key' => 'pol_coefficients',
                'value' => [
                    'intercept' => -14.610969282681078,
                    'coefficients' => [
                        -0.0006451500863823755,
                        -0.12123269418651915,
                        0.32632263966751446,
                        -0.2071431499208595,
                        -0.13015680935651952,
                        0.17531784016786284
                    ]
                ],
            ],
        ];

        foreach ($configurations as $config) {
            Configuration::updateOrCreate(
                ['key' => $config['key']],
                ['value' => $config['value']]
            );
        }
    }
}
