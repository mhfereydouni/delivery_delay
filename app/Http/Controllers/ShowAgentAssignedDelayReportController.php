<?php

namespace App\Http\Controllers;

use App\Http\Resources\DelayReportResource;
use App\Models\Agent;
use App\Models\DelayReport;
use Illuminate\Support\Facades\DB;

class ShowAgentAssignedDelayReportController extends Controller
{
    public function __invoke(Agent $agent): DelayReportResource
    {
        if ($delayReport = DelayReport::query()->whereBelongsTo($agent)->whereNull('resolved_at')->oldest()->first()) {
            return DelayReportResource::make($delayReport);
        }

        DB::transaction(function () use ($agent) {
            $delayReport = DelayReport::query()
                ->whereNull('agent_id')
                ->whereNull('resolved_at')
                ->lockForUpdate()
                ->oldest()
                ->first();

            if (!is_null($delayReport) && is_null($delayReport->agent_id)) {
                $delayReport?->update(['agent_id' => $agent->id]);
            }
        }, 3);

        return DelayReportResource::make($delayReport);
    }
}
