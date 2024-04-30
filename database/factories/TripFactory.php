<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TripFactory extends Factory
{
    protected $model = Trip::class;

    public function definition(): array
    {
        return [
            'order_id' => fn() => Order::factory()->create()->id,
            'status' => $this->faker->randomElement(['ASSIGNED', 'AT_VENDOR', 'PICKED', 'DELIVERED']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function assigned(): static
    {
        return $this->state(['status' => 'ASSIGNED']);
    }

    public function atVendor(): static
    {
        return $this->state(['status' => 'AT_VENDOR']);
    }

    public function picked(): static
    {
        return $this->state(['status' => 'PICKED']);
    }

    public function delivered(): static
    {
        return $this->state(['status' => 'DELIVERED']);
    }
}
