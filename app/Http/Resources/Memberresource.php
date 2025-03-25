<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Memberresource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'country_code' => $this->country_code,
            'mobile_number' => $this->mobile_number,
            'email' => $this->email,
            'location' => $this->location,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_mobile' => $this->emergency_contact_mobile,
            'medical_condition' => $this->medical_condition,
            'medical_notes' => $this->medical_notes,
            'photo' => $this->photo,
            'lead_source' => $this->lead_sources,
            'blacklisted' => (bool) $this->blacklisted,
            'last_contacted_at' => $this->last_contacted_at,
            'next_follow_up_at' => $this->next_follow_up_at,
            'converted_at' => $this->converted_at,
            'last_membership_item_id' => $this->last_membeship_item_id,
            'biometric_data' => $this->biometric_data,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'fitness_goals' => $this->fitness_goals,
            'preferred_workout_time' => $this->preferred_workout_time,
            'preferred_contact_method' => $this->preferred_contact_method,
            'interested_in' => $this->interested_in,
        ];
    }
}
