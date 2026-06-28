<?php

namespace App\Http\Resources\Orders;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderCancelPolicyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        return [
            'id' => $this->id,
            'fine_type' => $this->fine_type,
            'available_type' => $this->available_type,
            'time_in_min' => $this->time_in_min ?? 0,
            'fine_amount'=> $this->amount,
            'description' => $this->description,
         
            
        ];
    }
}
