<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexVendorsDelayResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'vendor_id' => $this->vendor_id,
            'total_delay' => $this->total_delay,
        ];
    }
}
