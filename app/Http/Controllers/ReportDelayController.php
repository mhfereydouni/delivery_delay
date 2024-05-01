<?php

namespace App\Http\Controllers;

use App\Actions\GetNewDeliveryEstimation;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class ReportDelayController extends Controller
{
    public function __invoke(GetNewDeliveryEstimation $getNewDeliveryEstimation, Order $order): JsonResponse
    {
        if ($order->trip()->whereIn('status', ['ASSIGNED', 'AT_VENDOR', 'PICKED'])->exists()) {
            $newDeliveryTime = ($getNewDeliveryEstimation)($order->id);

            $order->delayReports()->create(['resolved_at' => Carbon::now()]);

            return response()->json(['data' => [
                'new_delivery_time' => $newDeliveryTime,
                'will_arrive_at' => Carbon::now()->addMinutes($newDeliveryTime)->toDateTimeString()
            ]]);
        }

        if ($order->delayReports()->whereNull('resolved_at')->exists()) {

            return response()->json('Your last delay report is still under investigation.');
        }


        $order->delayReports()->create();

        return response()->json('Your order delivery will be investigated by agents.', 201);
    }
}
