<?php

namespace App\Http\Controllers;

use App\Http\Resources\IndexVendorsDelayResource;
use App\Models\Order;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;

class IndexVendorsDelayController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
        return IndexVendorsDelayResource::collection(Order::query()
            ->selectRaw('orders.vendor_id as vendor_id, SUM(TIMESTAMPDIFF(MINUTE,orders.created_at,trips.created_at) - orders.delivery_time) as total_delay')
            ->leftJoin('trips', 'orders.id', '=', 'trips.order_id')
            ->where('status', 'DELIVERED')
            ->where('orders.created_at', '>=', Carbon::now()->subWeek())
            ->whereHas('delayReports')
            ->groupBy('orders.vendor_id')
            ->orderByDesc('total_delay')
            ->get());
    }
}
