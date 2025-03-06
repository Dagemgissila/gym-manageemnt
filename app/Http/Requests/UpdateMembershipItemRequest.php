<?php

namespace App\Http\Requests;

use App\Enums\MembershipItem;
use App\Enums\YesNo;
use Illuminate\Foundation\Http\FormRequest;

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
            'membership_name' => 'required|string|max:255|unique:membership_items,membership_name',
            'description' => 'nullable|string',
            'membership_type_id' => 'required|exists:membership_types,id',
            'duration_days' => 'required|integer|min:1',
            'upgradable' => 'required|boolean',
            'price' => 'required|numeric|min:0',
            'discount_available' => 'required|boolean',
            'installment_available' => 'required|boolean',
            'free_freezes_allowed' => 'required|integer|min:0',
            'freeze_duration_max_weeks' => 'required|integer|min:0',
            'paid_freeze_allowed' => 'required|in:' . implode(',', YesNo::getValues()),
            'gym_access' => 'required|boolean',
            'status' => 'required|in:' . implode(',', MembershipItem::getValues()),
        ];
    }
}
