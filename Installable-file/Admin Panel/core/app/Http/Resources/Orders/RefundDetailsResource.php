<?php

namespace App\Http\Resources\Orders;

use App\Actions\Services\ImageModifier;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\RefundGateway;
use Illuminate\Http\Resources\Json\JsonResource;

class RefundDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        $gateway_name=null;
        if($this->gateway_id)
        {
            $gateway=RefundGateway::where("id",$this->gateway_id)->first();
            $gateway_name=$gateway?->name;
        }
        return [
            'id' => $this->id,
            'cancel_reason' => $this->cancel_reason,
            'gateway_id'=>$this->gateway_id,
            'gateway_name'=>$gateway_name,
            "gateway_fields"=>json_decode($this->gateway_fields, true),
            'amount' => $this->amount,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
