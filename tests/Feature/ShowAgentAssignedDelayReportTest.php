<?php

namespace Tests\Feature;

use App\Models\Agent;
use App\Models\DelayReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowAgentAssignedDelayReportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function agent_get_empty_response_where_there_is_not_a_delay_report(): void
    {
        $agent = Agent::factory()->create();

        $this->getJson(route('agents.delay-report', ['agent' => $agent]))
            ->assertOk()
            ->assertJson([]);

        DelayReport::factory()->resolved()->create();
        DelayReport::factory()->withoutAgent()->resolved()->create();
        DelayReport::factory()->for($agent)->resolved()->create();

        $this->getJson(route('agents.delay-report', ['agent' => $agent]))
            ->assertOk()
            ->assertJson([]);
    }

    /** @test */
    public function agent_can_get_a_delay_report_assigned_to_himself(): void
    {
        $agent = Agent::factory()->create();

        DelayReport::factory()->withoutAgent()->resolved()->create();
        DelayReport::factory()->resolved()->create();
        DelayReport::factory()->create();
        DelayReport::factory()->for($agent)->resolved()->create();
        $delayReport = DelayReport::factory()->withoutAgent()->create();
        DelayReport::factory()->withoutAgent()->create();

        $this->getJson(route('agents.delay-report', ['agent' => $agent]))
            ->assertOk()
            ->assertJson(['data' => [
                'order_id' => $delayReport->order_id,
                'agent_id' => $agent->id,
                'resolved_at' => null,
                'created_at' => $delayReport->created_at->toJson(),
                'updated_at' => $delayReport->refresh()->updated_at->toJson(),
            ]]);

        $this->assertDatabaseHas(DelayReport::class, [
            'id' => $delayReport->id,
            'order_id' => $delayReport->order_id,
            'agent_id' => $agent->id,
            'resolved_at' => null,
        ]);
    }

    /** @test */
    public function agent_do_not_get_new_delay_report_if_he_has_ongoing_report(): void
    {
        $agent = Agent::factory()->create();

        DelayReport::factory()->withoutAgent()->resolved()->create();
        DelayReport::factory()->resolved()->create();
        DelayReport::factory()->create();
        DelayReport::factory()->for($agent)->resolved()->create();
        $delayReport = DelayReport::factory()->for($agent)->create();
        DelayReport::factory()->withoutAgent()->create();

        $this->getJson(route('agents.delay-report', ['agent' => $agent]))
            ->assertOk()
            ->assertJson(['data' => [
                'order_id' => $delayReport->order_id,
                'agent_id' => $agent->id,
                'resolved_at' => null,
                'created_at' => $delayReport->created_at->toJson(),
                'updated_at' => $delayReport->updated_at->toJson(),
            ]]);
    }
}
