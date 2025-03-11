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
        'upgradable_limit',
        'price',
        'discount_available',
        'installment_available',
        'free_freezes_allowed',
        'freeze_duration_max_weeks',
        'paid_freeze_allowed',
        'gym_access',
        'status',
        "suspend_based_on_balance",
        'suspend_after',
        'accessible_days',
        'sessions',
        'created_by',
        'updated_by',
        'link_access_to_booked_appts'
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


    public function dateTimeRestrictions()
    {
        return $this->hasMany(DateTimeRestriction::class, 'membership_item_id');
    }
}
