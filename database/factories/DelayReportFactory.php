<?php

namespace Database\Factories;

use App\Models\Agent;
use App\Models\DelayReport;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DelayReportFactory extends Factory
{
    protected $model = DelayReport::class;

    public function definition(): array
    {
        return [
            'order_id' => fn() => Order::factory()->create()->id,
            'agent_id' => fn() => Agent::factory()->create()->id,
            'resolved_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function resolved(): static
    {
        return $this->state(['resolved_at' => Carbon::now()]);
    }

    public function withoutAgent(): static
    {
        return $this->state(['agent_id' => null]);
    }
}
