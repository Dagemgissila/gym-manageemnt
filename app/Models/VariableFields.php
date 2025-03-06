<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class VariableFields extends Model
{
    //

    use Filterable;


    protected $searchable = ['value'];

    // Filters that can be applied
    protected $allowedFilters = ['status'];

    // Default sorting field
    protected $defaultSortField = 'id';

    // Default sorting order
    protected $defaultSortOrder = 'desc';


    public function fieldContent()
    {
        return $this->belongsTo(FieldContent::class, "field_content_id");
    }
}
