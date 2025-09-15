<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WeeklyPricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('weekly_prices')->insert([
            [
                "week_of"     => Carbon::parse('2025-09-01'),
                "b_domestic"  => 2100.00,
                "a_us_quota"  => null,
                "molasses_mt" => null,
                "source"      => 'SRA Weekly Millsite Prices',
                "created_at"  => now(),
                "updated_at"  => now(),
            ],
            [
                "week_of"     => Carbon::parse('2025-09-08'),
                "b_domestic"  => 2125.00,
                "a_us_quota"  => null,
                "molasses_mt" => null,
                "source"      => 'SRA Weekly Millsite Prices',
                "created_at"  => now(),
                "updated_at"  => now(),
            ],
        ]);
    }
}
