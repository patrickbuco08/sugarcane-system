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
                    'intercept' => 15.742419778770774,
                    'coefficients' => [
                        0.0069767031384961485,
                        -0.01488018647851385,
                        0.013301487058016963,
                        -0.06403372035031844,
                        0.010876751925557255,
                        0.0
                    ]
                ],
            ],
            [
                'key' => 'pol_coefficients',
                'value' => [
                    'intercept' => 12.971504748127577,
                    'coefficients' => [
                        0.010230301578094642,
                        -0.024825754388933287,
                        0.0036046238367258,
                        -0.0644299687403385,
                        0.020730397894996345,
                        0.0
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
