<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{

    use Filterable;
    protected $fillable = ["base_currency_id", "foreign_currency_id", "exchange_rate", "date", "is_exchnage_currency_reverse"];

    protected $searchable = [''];

    // Filters that can be applied
    protected $allowedFilters = [''];

    // Default sorting field
    protected $defaultSortField = 'id';

    // Default sorting order
    protected $defaultSortOrder = 'desc';

    public function BaseCurrency()
    {
        return $this->belongsTo(BaseCurrency::class, "base_currency_id");
    }

    public function ForeignCurrency()
    {
        return $this->belongsTo(ForeignCurrency::class, "foreign_currency_id");
    }
}
