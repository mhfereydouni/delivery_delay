<?php

namespace Database\Factories;

use App\Enums\TripStatus;
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
            'status' => $this->faker->randomElement(TripStatus::cases()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function assigned(): static
    {
        return $this->state(['status' => TripStatus::ASSIGNED]);
    }

    public function atVendor(): static
    {
        return $this->state(['status' => TripStatus::AT_VENDOR]);
    }

    public function picked(): static
    {
        return $this->state(['status' => TripStatus::PICKED]);
    }

    public function delivered(): static
    {
        return $this->state(['status' => TripStatus::DELIVERED]);
    }

    public function notDelivered(): static
    {
        return $this->state(['status' => $this->faker->randomElement(TripStatus::notDelivered())]);
    }
}
