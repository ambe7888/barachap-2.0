<?php

namespace App\Http\Resources\Orders;

use App\Actions\Services\ImageModifier;
use App\Http\Resources\Services\ServiceSummaryResource;
use App\Http\Resources\StaffResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\JobPost\app\Resources\JobListsPublicResource;

class ProviderSubOrderListResource extends JsonResource
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
            'order_id' => $this->order_id,
            'payment_gateway' => $this->order ? $this->order?->payment_gateway : null,
            'service_id' => $this->service_id,
            'service_image' => $this->service ? ImageModifier::ImageUrl($this->service?->image) : null,
            'job_post_id' => $this->job_post_id,
            'provider_id' => $this->provider_id,
            'staff_id' => $this->staff_id,
            'admin_id' => $this->admin_id,
            'client_id' => $this->client_id,
            'client_image' => $this->client ? ImageModifier::ImageUrl($this->client?->image) : null,
            'date' => $this->date,
            'schedule' => $this->schedule,
            'basic_price' => $this->basic_price,
            'sub_total' => $this->sub_total,
            'tax' => $this->tax,
            'total' => $this->total,
            'commission_type' => $this->commission_type,
            'commission_charge' => $this->commission_charge,
            'commission_amount' => $this->commission_amount,
            'order_note' => $this->order_note,
            'complete_request' => $this->complete_request,
            'payment_status' => $this->payment_status,
            'status' => $this->status,
            'subOrderAddons' => SubOrderAddonResource::collection($this->subOrderAddons),
            'subOrderLocations' => $this->subOrderLocations ? new SubOrderLocationResource($this->subOrderLocations) : null,
            'staff' => new StaffResource($this->staff),
        ];
    }
}
