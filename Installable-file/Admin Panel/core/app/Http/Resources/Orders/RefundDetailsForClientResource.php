<?php

namespace App\Http\Resources\Orders;

use App\Actions\Services\ImageModifier;
use App\Http\Resources\AdminResource;
use App\Http\Resources\ClientResource;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\RefundGateway;
use App\Http\Resources\ProviderResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Backend\Admin;

class RefundDetailsForClientResource extends JsonResource
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
            $gateway_name=$gateway->name;
        }
        $provider=User::where("id",$this->provider_id)->first();
        $client=User::where("id",$this->client_id)->first();
        $admin=Admin::where("id",$this->admin_id)->first();
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'sub_order_id' => $this->sub_order_id,
            'client_id' => $client ? new ClientResource($client) : null,
            'provider_id' => $provider ? new ProviderResource($provider) : null,
            'admin_id' => $admin ? new AdminResource($admin) : null,
            'cancel_reason' => $this->cancel_reason,
            'gateway_id'=>$this->gateway_id,
            'gateway_name'=>$gateway_name,
            "gateway_fields"=>json_decode($this->gateway_fields, true),
            'amount' => $this->amount,
            'status' => $this->status,
            'user_type' =>$this->user_type,
            'current_suborder_status' => $this->current_suborder_status,
            'add_fee' => $this->add_fee,
            'created_at' => $this->created_at,
        ];
    }
}
