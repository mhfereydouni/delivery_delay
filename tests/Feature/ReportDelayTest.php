<?php

namespace Tests\Feature;

use App\Models\DelayReport;
use App\Models\Order;
use App\Models\Trip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ReportDelayTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_not_submit_a_delay_report_for_an_order_which_has_remaining_delivery_time(): void
    {
        $order = Order::factory()->create(['delivery_time' => 10]);

        $this->postJson(route('orders.report-delay', ['order' => $order]))
            ->assertForbidden();
    }

    /** @test */
    public function user_can_submit_a_delay_report_for_an_order_which_has_no_trip(): void
    {
        $order = Order::factory()->create(['delivery_time' => 10]);

        $this->travel(11)->minutes();

        $this->postJson(route('orders.report-delay', ['order' => $order]))
            ->assertCreated();

        $this->assertDatabaseHas(DelayReport::class, [
            'order_id' => $order->id,
            'agent_id' => null,
            'resolved_at' => null,
        ]);
    }

    /** @test */
    public function user_can_submit_a_delay_report_for_an_order_which_has_a_delivered_trip(): void
    {
        $order = Order::factory()->create(['delivery_time' => 10]);

        Trip::factory()->delivered()->for($order)->create();

        $this->travel(11)->minutes();

        $this->postJson(route('orders.report-delay', ['order' => $order]))
            ->assertCreated();

        $this->assertDatabaseHas(DelayReport::class, [
            'order_id' => $order->id,
            'agent_id' => null,
            'resolved_at' => null,
        ]);
    }

    /** @test */
    public function user_can_submit_a_delay_report_for_an_order_which_has_a_trip_but_it_is_not_delivered_and_get_new_eta(): void
    {
        $order = Order::factory()->create(['delivery_time' => 0]);

        Http::fake([
            'localhost/orders/' . $order->id . '/eta-mock' => Http::response([
                'data' => ['eta' => $newEta = random_int(10, 100)]
            ]),
        ]);


        Trip::factory()->notDelivered()->for($order)->create();

        $this->freezeTime(function () use ($newEta, $order) {
            $this
                ->postJson(route('orders.report-delay', ['order' => $order]))
                ->assertOk()
                ->assertJson([
                    'data' => [
                        'new_delivery_time' => $newEta,
                        'will_arrive_at' => Carbon::now()->addMinutes($newEta)->toDateTimeString()
                    ]
                ]);

            $this->assertDatabaseHas(DelayReport::class, [
                'order_id' => $order->id,
                'agent_id' => null,
                'resolved_at' => Carbon::now(),
            ]);

            Http::assertSent(
                fn(Request $request) => $request->method() === 'GET'
                    && $request->url() === 'localhost/orders/' . $order->id . '/eta-mock'
            );
        });
    }
}
