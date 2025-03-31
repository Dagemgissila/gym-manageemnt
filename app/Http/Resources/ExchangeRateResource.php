<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExchangeRateResource extends JsonResource
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
            "base_currency" => new BaseCurrencyResource($this->whenLoaded("BaseCurrency")),
            "foreign_currency" => new ForeignCurrencyresource($this->whenLoaded("ForeignCurrency")),
            "exchange_rate" => $this->exchange_rate,
            "is_exchnage_currency_reverse" => $this->is_exchnage_currency_reverse,
            "date" => $this->date
        ];
    }
}
