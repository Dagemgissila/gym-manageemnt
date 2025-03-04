<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MembershipItemResource extends JsonResource
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
            'membership_name' => $this->membership_name,
            'description' => $this->description,
            'membership_type' => new MembershipTypeResource($this->whenLoaded('membershipType')),
            'duration_days' => $this->duration_days,
            'upgradable' => $this->upgradable,
            'price' => $this->price,
            'discount_available' => $this->discount_available,
            'installment_available' => $this->installment_available,
            'free_freezes_allowed' => $this->free_freezes_allowed,
            'freeze_duration_max_weeks' => $this->freeze_duration_max_weeks,
            'paid_freeze_allowed' => $this->paid_freeze_allowed,
            'gym_access' => $this->gym_access,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
