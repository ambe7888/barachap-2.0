<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentGatewayResourceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $image = get_attachment_image_by_id($this['image']);
        $image_url = !empty($image) ? $image['img_url'] : '';

        return [
            "name" => $this['name'] ?? '',
            "image" => $image_url,
            "description" => $this['description'] ?? '',
            "status" => $this['status'] ?? '',
            "test_mode" => $this['test_mode'] ?? '',
            "credentials" => json_decode($this['credentials'] ?? "")
        ];
    }
}
