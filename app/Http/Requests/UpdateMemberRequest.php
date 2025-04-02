<?php

namespace App\Http\Requests;

use App\Models\FieldValidation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberRequest extends FormRequest
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
        $memberType = "membership";
        $requiredFields = FieldValidation::where($memberType, 'YES')->get();

        // Get the member ID from route parameters
        $memberId = $this->route('member')->id ?? $this->route('id');

        $rules = [];

        foreach ($requiredFields as $field) {
            switch ($field->input_type) {
                case 'text':
                    $rules[$field->field_key] = 'required|string|max:255';
                    break;
                case 'email':
                    // Add exception for current member
                    $rules[$field->field_key] = 'required|email|unique:members,email,' . $memberId;
                    break;
                case 'date':
                    $rules[$field->field_key] = 'required|date';
                    break;
                case 'tel':
                    $rule = 'required|string|min:10|max:15';
                    // Add unique exception only for mobile_number field
                    if ($field->field_key === 'mobile_number') {
                        $rule .= '|unique:members,mobile_number,' . $memberId;
                    }
                    $rules[$field->field_key] = $rule;
                    break;
                case 'toggle':
                    $rules[$field->field_key] = 'sometimes|boolean';
                    break;
                case 'file':
                    $rules[$field->field_key] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
                    break;
                case 'dropdown':
                    $rules[$field->field_key] = $field->is_multiple == 1 ? 'required|array' : 'required|string';
                    break;
                case 'gender':
                    $rules[$field->field_key] = 'required|in:male,female,other';
                    break;
                default:
                    $rules[$field->field_key] = 'required';
                    break;
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
            'email.unique' => 'This email is already registered.',

            'mobile_number.required' => 'Mobile number is required.',
            'mobile_number.string' => 'Mobile number must be a valid string.',
            'mobile_number.min' => 'Mobile number must be at least 10 digits.',
            'mobile_number.max' => 'Mobile number must not exceed 15 digits.',
            'mobile_number.unique' => 'This mobile number is already registered.',

            'emergency_contact_mobile.required' => 'Emergency contact mobile is required.',
            'emergency_contact_mobile.string' => 'Emergency contact mobile must be a valid string.',
            'emergency_contact_mobile.min' => 'Emergency contact mobile must be at least 10 digits.',
            'emergency_contact_mobile.max' => 'Emergency contact mobile must not exceed 15 digits.',
            'emergency_contact_mobile.unique' => 'This emergency contact mobile is already registered.',

            'in' => 'Invalid selection for :attribute.',
            'image' => 'Only image files are allowed.',
            'mimes' => 'Allowed formats: jpeg, png, jpg, gif.',
            'max.string' => 'The :attribute must not exceed :max characters.',
            'max.file' => 'File size must be under 2MB.'
        ];
    }
}
