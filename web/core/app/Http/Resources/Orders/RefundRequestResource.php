<?php

namespace App\Http\Resources\Orders;

use App\Actions\Services\ImageModifier;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\RefundGateway;
use Illuminate\Http\Resources\Json\JsonResource;

class RefundRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $order=Order::findOrFail($this->order_id);
        $gateway_name=null;
        if($this->gateway_id)
        {
            $gateway=RefundGateway::where("id",$this->gateway_id)->first();
            $gateway_name=$gateway?->name;
        }
        
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'sub_order_id' => $this->sub_order_id,
            'client_id' => $this->client_id,
            'provider_id' => $this->provider_id,
            'admin_id' => $this->admin_id,
            'cancel_reason' => $this->cancel_reason,
            'amount' => $this->amount,
            'gateway_id'=>$this->gateway_id,
            'gateway_name'=>$gateway_name,
            "gateway_fields"=>json_decode($this->gateway_fields, true),
            'image'=>$this->image,
            'status' => $this->status,
           'user_type' =>$this->user_type,
           'current_suborder_status' => $this->current_suborder_status,
           'add_fee' => $this->add_fee,
            'created_at' => $this->created_at,
        ];
    }
}
