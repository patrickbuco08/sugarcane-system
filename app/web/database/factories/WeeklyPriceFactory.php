<?php

namespace Database\Factories;

use Bocum\Models\WeeklyPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Bocum\Models\WeeklyPrice>
 */
class WeeklyPriceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WeeklyPrice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'week_of' => $this->faker->dateTimeBetween('-6 months', '+1 month')->format('Y-m-d'),
            'b_domestic' => $this->faker->randomFloat(2, 2000, 3000), // ₱2000-3000/LKG
            'a_us_quota' => $this->faker->optional(0.7)->randomFloat(2, 2500, 3500), // Optional, ₱2500-3500/LKG
            'molasses_mt' => $this->faker->optional(0.6)->randomFloat(2, 1000, 1500), // Optional, ₱1000-1500/MT
            'source' => $this->faker->optional(0.8)->randomElement([
                'SRA Weekly Millsite Prices',
                'Sugar Regulatory Administration',
                'Philippine Sugar Millers Association',
                'Department of Agriculture',
            ]),
        ];
    }

    /**
     * Indicate that the weekly price is for a specific week.
     */
    public function forWeek(string $weekOf): static
    {
        return $this->state(fn (array $attributes) => [
            'week_of' => $weekOf,
        ]);
    }

    /**
     * Indicate that the weekly price has all optional fields filled.
     */
    public function complete(): static
    {
        return $this->state(fn (array $attributes) => [
            'a_us_quota' => $this->faker->randomFloat(2, 2500, 3500),
            'molasses_mt' => $this->faker->randomFloat(2, 1000, 1500),
            'source' => 'SRA Weekly Millsite Prices',
        ]);
    }

    /**
     * Indicate that the weekly price has only required fields.
     */
    public function minimal(): static
    {
        return $this->state(fn (array $attributes) => [
            'a_us_quota' => null,
            'molasses_mt' => null,
            'source' => null,
        ]);
    }
}
