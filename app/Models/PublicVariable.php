<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class PublicVariable extends Model
{
    use Filterable;

    // Fields that can be searched
    protected $searchable = ['setting_text'];

    // Filters that can be applied
    protected $allowedFilters = [''];

    // Default sorting field
    protected $defaultSortField = 'id';

    // Default sorting order
    protected $defaultSortOrder = 'desc';
}
