<?php

namespace Tests\Feature;

use Bocum\Models\User;
use Bocum\Models\WeeklyPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WeeklyPriceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    /** @test */
    public function it_can_display_weekly_prices_index()
    {
        // Create some test weekly prices
        WeeklyPrice::factory()->count(3)->create();

        $response = $this->get(route('weekly-prices.index'));

        $response->assertStatus(200);
        $response->assertViewIs('weekly-prices.index');
        $response->assertViewHas('weeklyPrices');
    }

    /** @test */
    public function it_can_display_create_form()
    {
        $response = $this->get(route('weekly-prices.create'));

        $response->assertStatus(200);
        $response->assertViewIs('weekly-prices.create');
    }

    /** @test */
    public function it_can_store_a_weekly_price()
    {
        $weeklyPriceData = [
            'week_of' => '2025-09-22', // Monday
            'b_domestic' => 2500.50,
            'a_us_quota' => 2800.75,
            'molasses_mt' => 1200.00,
            'source' => 'SRA Weekly Millsite Prices'
        ];

        $response = $this->post(route('weekly-prices.store'), $weeklyPriceData);

        $response->assertRedirect(route('weekly-prices.index'));
        $response->assertSessionHas('success', 'Weekly price created successfully.');
        
        $this->assertDatabaseHas('weekly_prices', [
            'week_of' => '2025-09-22',
            'b_domestic' => 2500.50,
        ]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->post(route('weekly-prices.store'), []);

        $response->assertSessionHasErrors(['week_of', 'b_domestic']);
    }

    /** @test */
    public function it_prevents_duplicate_week_of_dates()
    {
        WeeklyPrice::factory()->create(['week_of' => '2025-09-22']);

        $response = $this->post(route('weekly-prices.store'), [
            'week_of' => '2025-09-22',
            'b_domestic' => 2500.50,
        ]);

        $response->assertSessionHasErrors(['week_of']);
    }

    /** @test */
    public function it_can_display_edit_form()
    {
        $weeklyPrice = WeeklyPrice::factory()->create();

        $response = $this->get(route('weekly-prices.edit', $weeklyPrice));

        $response->assertStatus(200);
        $response->assertViewIs('weekly-prices.edit');
        $response->assertViewHas('weeklyPrice', $weeklyPrice);
    }

    /** @test */
    public function it_can_update_a_weekly_price()
    {
        $weeklyPrice = WeeklyPrice::factory()->create();

        $updateData = [
            'week_of' => '2025-09-29',
            'b_domestic' => 2600.00,
            'a_us_quota' => 2900.00,
            'molasses_mt' => 1300.00,
            'source' => 'Updated Source'
        ];

        $response = $this->put(route('weekly-prices.update', $weeklyPrice), $updateData);

        $response->assertRedirect(route('weekly-prices.index'));
        $response->assertSessionHas('success', 'Weekly price updated successfully.');
        
        $this->assertDatabaseHas('weekly_prices', [
            'id' => $weeklyPrice->id,
            'b_domestic' => 2600.00,
        ]);
    }

    /** @test */
    public function it_can_delete_a_weekly_price_without_harvest_batches()
    {
        $weeklyPrice = WeeklyPrice::factory()->create();

        $response = $this->delete(route('weekly-prices.destroy', $weeklyPrice));

        $response->assertRedirect(route('weekly-prices.index'));
        $response->assertSessionHas('success', 'Weekly price deleted successfully.');
        
        $this->assertDatabaseMissing('weekly_prices', [
            'id' => $weeklyPrice->id,
        ]);
    }
}
