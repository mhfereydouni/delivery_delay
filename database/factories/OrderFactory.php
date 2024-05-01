<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'vendor_id' => fn() => Vendor::factory()->create()->id,
            'delivery_time' => random_int(10, 120),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
