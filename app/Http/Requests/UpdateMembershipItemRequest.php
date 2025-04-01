<?php

namespace App\Http\Requests;

use App\Enums\MembershipFor;
use App\Enums\MembershipItem;
use App\Enums\YesNo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMembershipItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'membership_name' => 'required|string|max:255',
            Rule::unique('membership_items')->ignore($this->id),
            'description' => 'nullable|string',
            'membership_type_id' => 'required|exists:membership_types,id',
            'duration_days' => 'required|integer|min:1',
            'upgradable_limit' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'discount_available' => 'required|boolean',
            'installment_available' => 'required|boolean',
            'free_freezes_allowed' => 'required|integer|min:0',
            'freeze_duration_max_weeks' => 'required|integer|min:0',
            'validity' => 'required|integer|min:1',
            'paid_freeze_allowed' => 'required|in:' . implode(',', YesNo::getValues()),
            'membership_for' => 'required|in:' . implode(',', MembershipFor::getValues()),
            'gym_access' => 'required|boolean',
            'status' => 'required|boolean',
            "suspend_based_on_balance" => 'required|numeric|min:0',
            'suspend_after' => 'required|numeric|min:0',
            'accessible_days' => 'required|numeric|min:0',
            'sessions' => 'required|numeric|min:0',
            'link_access_to_booked_appts' => 'nullable|boolean',
            'selected_days' => 'nullable|array', // Ensure selected_days is an array
            'selected_days.*.day' => 'required|string', // Validate each day entry
            'selected_days.*.from_time' => 'required|string', // Change start_time to from_time
            'selected_days.*.to_time' => 'required|string', // Change end_time to to_time
        ];
    }
}
