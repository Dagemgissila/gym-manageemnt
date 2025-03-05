<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class PublicRule extends Model
{
    use Filterable;

    protected $fillable = ["setting_rule", "setting_value", "status"];

    // Fields that can be searched
    protected $searchable = ['setting_rule'];

    // Filters that can be applied
    protected $allowedFilters = [''];

    // Default sorting field
    protected $defaultSortField = 'id';

    // Default sorting order
    protected $defaultSortOrder = 'desc';
}
