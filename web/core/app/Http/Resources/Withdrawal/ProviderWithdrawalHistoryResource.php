<?php

namespace App\Http\Resources\Withdrawal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\PaymentGateways\app\Models\PaymentGateway;

class ProviderWithdrawalHistoryResource extends JsonResource
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
               'amount' => number_format($this->amount, 2, '.', ','),
               'gateway_id' => $this->gateway_id,
               'gateway_name' => PaymentGateway::find($this->gateway_id)->name ?? null,
               'user_id' => $this->user_id,
               'gateway_fields' => $this->gateway_fields,
               'fee' => $this->fee,
               'note' => $this->note,
               'status' => $this->status,
               'image' => $this->image ? asset('assets/uploads/withdraw-request/' . $this->image) : null,
           ];
    }
}
