<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class GetNewDeliveryEstimation
{
    public function __invoke(int $orderId): int
    {
        return Http::withoutVerifying()
            ->acceptJson()
            ->asJson()
            ->get('localhost/orders/'.$orderId.'/eta-mock')
            ->json('data.eta');
    }
}
