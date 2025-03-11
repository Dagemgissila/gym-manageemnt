<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DateTimeRstrictionResource extends JsonResource
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
            'membership_item_id' => $this->membership_item_id,
            'day' => $this->day,
            'from_time' => $this->from_time,
            'to_time' => $this->to_time,
            'time_period' => $this->time_period,

        ];
    }
}
