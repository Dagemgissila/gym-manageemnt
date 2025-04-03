<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "voucher_id" => $this->voucher_id,
            "member_id" => $this->member_id,
            "validity" => $this->validity,
            "amount" => $this->amount,
            "comment" => $this->comment
        ];
    }
}
