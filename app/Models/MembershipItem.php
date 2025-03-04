<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class MembershipItem extends Model
{
    use Filterable;

    protected $fillable = [
        'membership_name',
        'description',
        'membership_type_id',
        'duration_days',
        'upgradable',
        'price',
        'discount_available',
        'installment_available',
        'free_freezes_allowed',
        'freeze_duration_max_weeks',
        'paid_freeze_allowed',
        'gym_access',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $searchable = ['membership_name'];

    // Filters that can be applied
    protected $allowedFilters = [''];

    // Default sorting field
    protected $defaultSortField = 'id';

    // Default sorting order
    protected $defaultSortOrder = 'desc';


    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class, "membership_type_id");
    }

}
