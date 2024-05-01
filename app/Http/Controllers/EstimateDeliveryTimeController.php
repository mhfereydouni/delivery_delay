<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;

class EstimateDeliveryTimeController extends Controller
{
    public function __invoke(Order $order): JsonResponse
    {
        return response()->json(['data' => ['eta' => random_int(10, 100)]]);
    }
}
