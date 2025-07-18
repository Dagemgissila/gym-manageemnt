<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class VariableFields extends Model
{
    //

    use Filterable;

    protected $fillable = ['field_content_id', 'value', 'status'];
    protected $searchable = ['value'];

    // Filters that can be applied
    protected $allowedFilters = ['status', 'field_content_id'];

    // Default sorting field
    protected $defaultSortField = 'id';

    // Default sorting order
    protected $defaultSortOrder = 'desc';


    public function scopeFieldContentId($query, $fieldContentId)
    {
        $query->whereHas('fieldContent', function ($q) use ($fieldContentId) {
            $q->where('id', $fieldContentId);
        });
    }

    public function fieldContent()
    {
        return $this->belongsTo(FieldContent::class, "field_content_id");
    }
}
