<?php

namespace App\Http\Requests;

use App\Enums\MembershipBase;
use Illuminate\Foundation\Http\FormRequest;

class StoreMembershipTypeRequest extends FormRequest
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
            'membership_type' => ['required', 'string', 'max:255', 'unique:membership_types,membership_type'],
            'membership_base' => ['required', 'string', 'max:255', 'in:' . implode(",", MembershipBase::getValues())],
            'live_membership' => ['boolean'],
            'background_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'membership_overlap' => ['boolean'],
            'status' => ['required', 'boolean']
        ];
    }


}
