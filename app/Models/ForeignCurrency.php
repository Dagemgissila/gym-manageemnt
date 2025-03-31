<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class ForeignCurrency extends Model
{
    use Filterable;

    protected $fillable = ['code', 'name', 'symbol', 'decimal_place'];


    protected $searchable = ['code', 'name', 'symbol'];

    protected $allowedFilters = [''];


    // Default sorting field
    protected $defaultSortField = 'id';

    // Default sorting order
    protected $defaultSortOrder = 'desc';

}
