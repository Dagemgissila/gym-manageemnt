<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    //
    use HasFactory, Filterable;


    // Fields that can be searched
    protected $searchable = ['first_name', 'last_name', 'email', 'mobile_number'];

    // Filters that can be applied
    protected $allowedFilters = ['status'];

    // Default sorting field
    protected $defaultSortField = 'id';

    // Default sorting order
    protected $defaultSortOrder = 'desc';
    protected $fillable = [
        'first_name',
        'last_name',
        'country_code',
        'mobile_number',
        'email',
        'location',
        'gender',
        'date_of_birth',
        'emergency_contact_name',
        'emergency_contact_mobile',
        'medical_condition',
        'medical_notes',
        'photo',
        'lead_sources',
        'blacklisted',
        'last_contacted_at',
        'next_follow_up_at',
        'converted_at',
        'last_membeship_item_id',
        'biometric_data',
        'status',
        'interested_in',
        'fitness_goals',
        'notes',
        'preferred_workout_time',
        'preferred_contact_method',
    ];


    protected $casts = [
        'lead_sources' => 'array',
        'interested_in' => 'array',
        'fitness_goals' => 'array',
        'preferred_workout_time' => 'array',
        'preferred_contact_method' => 'array',
    ];



}
