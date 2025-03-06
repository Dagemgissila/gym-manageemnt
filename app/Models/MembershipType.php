<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class MembershipType extends Model
{
    use Filterable;
    protected $fillable = ['membership_type', 'is_session_based', 'live_membership', 'background_color', 'membership_overlap', 'status'];

    // Fields that can be searched
    protected $searchable = ['membership_type'];

    // Filters that can be applied
    protected $allowedFilters = [''];

    // Default sorting field
    protected $defaultSortField = 'id';

    // Default sorting order
    protected $defaultSortOrder = 'desc';

}
