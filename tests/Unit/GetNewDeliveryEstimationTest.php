<?php

namespace Tests\Unit;

use App\Actions\GetNewDeliveryEstimation;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GetNewDeliveryEstimationTest extends TestCase
{
    /** @test */
    public function it_can_get_new_delivery_time(): void
    {
        Http::fake([
            'http://localhost/orders/1/eta-mock' => Http::response([
                'data' => ['eta' => $newEta = random_int(10, 100)],
            ]),
        ]);

        $this->assertEquals($newEta, (new GetNewDeliveryEstimation)(1));

        Http::assertSent(
            fn (Request $request) => $request->method() === 'GET'
                && $request->url() === 'http://localhost/orders/1/eta-mock'
        );

    }
}
