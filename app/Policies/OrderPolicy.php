<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Carbon;

class OrderPolicy
{
    use HandlesAuthorization;

    public function reportDelay(?User $user, Order $order): Response
    {
        return $order->created_at->addMinutes($order->delivery_time) > Carbon::now()
            ? Response::deny('The delivery time has not ended yet.')
            : Response::allow();
    }
}
