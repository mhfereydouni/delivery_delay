<?php

namespace Tests\Feature;

use App\Models\Agent;
use App\Models\DelayReport;
use App\Models\Order;
use App\Models\Trip;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class IndexVendorsDelayTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_vendors_that_have_delay_report_in_last_week_sorted(): void
    {
        $start = Carbon::now()->subDay();

        $agent1 = Agent::factory()->create();
        $agent2 = Agent::factory()->create();

        $vendor1 = Vendor::factory()->create();
        $vendor2 = Vendor::factory()->create();
        $vendor3 = Vendor::factory()->create();

        $order1_1 = Order::factory()->for($vendor1)->create([
            'created_at' => $start,
            'delivery_time' => 20,
        ]);
        $order1_2 = Order::factory()->for($vendor1)->create([
            'created_at' => $start,
            'delivery_time' => 30,
        ]);
        Trip::factory()->for($order1_2)->assigned()->create([
            'created_at' => $start,
        ]);
        Trip::factory()->for($order1_2)->atVendor()->create([
            'created_at' => $start,
        ]);
        Trip::factory()->for($order1_2)->picked()->create([
            'created_at' => $start,
        ]);
        DelayReport::factory()->for($order1_2)->withoutAgent()->resolved()->create([
            'created_at' => $start,
        ]);
        Trip::factory()->for($order1_2)->delivered()->create([
            'created_at' => $start->addMinutes(35),
        ]);

        $order1_3 = Order::factory()->for($vendor1)->create([
            'created_at' => $start,
            'delivery_time' => 20,
        ]);
        Trip::factory()->for($order1_3)->assigned()->create([
            'created_at' => $start,
        ]);
        Trip::factory()->for($order1_3)->atVendor()->create([
            'created_at' => $start,
        ]);

        $order1_4 = Order::factory()->for($vendor1)->create(['delivery_time' => 100]);

        // reset start time
        $start->subMinutes(35);

        $order2_1 = Order::factory()->for($vendor2)->create([
            'created_at' => $start->addHour(),
            'delivery_time' => 20,
        ]);
        $order2_2 = Order::factory()->for($vendor2)->create([
            'created_at' => $start,
            'delivery_time' => 20,
        ]);
        Trip::factory()->for($order2_2)->assigned()->create([
            'created_at' => $start,
        ]);
        Trip::factory()->for($order2_2)->atVendor()->create([
            'created_at' => $start,
        ]);
        Trip::factory()->for($order2_2)->picked()->create([
            'created_at' => $start,
        ]);
        DelayReport::factory()->for($order2_2)->withoutAgent()->resolved()->create([
            'created_at' => $start,
        ]);
        Trip::factory()->for($order2_2)->delivered()->create([
            'created_at' => $start->addMinutes(40),
        ]);

        $order2_3 = Order::factory()->for($vendor2)->create([
            'created_at' => $start,
            'delivery_time' => 20,
        ]);
        Trip::factory()->for($order2_3)->assigned()->create([
            'created_at' => $start,
        ]);
        Trip::factory()->for($order2_3)->atVendor()->create([
            'created_at' => $start,
        ]);

        $order2_4 = Order::factory()->for($vendor2)->create(['delivery_time' => 100]);
        Trip::factory()->for($order2_4)->assigned()->create([
            'created_at' => $start,
        ]);

        $order3_1 = Order::factory()->for($vendor3)->create([
            'created_at' => $start,
            'delivery_time' => 20,
        ]);

        $this->getJson(route('vendors.delays'))
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJson(['data' => [
                [
                    'vendor_id' => $vendor2->id,
                    'total_delay' => 20,
                ],
                [
                    'vendor_id' => $vendor1->id,
                    'total_delay' => 5,
                ],
            ]]);
    }
}
