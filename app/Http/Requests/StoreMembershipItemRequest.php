<?php

namespace App\Http\Requests;

use App\Enums\MembershipFor;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\YesNo;
use App\Enums\MembershipItem;

class StoreMembershipItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust authorization as needed.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'membership_name' => 'required|string|max:255|unique:membership_items,membership_name',
            'description' => 'nullable|string',
            'membership_type_id' => 'required|exists:membership_types,id',
            'duration_days' => 'required|integer|min:1',
            'upgradable_limit' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'discount_available' => 'sometimes|boolean',
            'installment_available' => 'sometimes|boolean',
            'free_freezes_allowed' => 'required|integer|min:0',
            'freeze_duration_max_weeks' => 'required|integer|min:0',
            'validity' => 'required|integer|min:1',
            'paid_freeze_allowed' => 'required|in:' . implode(',', YesNo::getValues()),
            'membership_for' => 'required|in:' . implode(',', MembershipFor::getValues()),
            'gym_access' => 'sometimes|boolean',
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
