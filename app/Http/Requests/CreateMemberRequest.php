<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\FieldValidation;
use App\Models\FieldContent;

class CreateMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $memberType = "membership";
        $requiredFields = FieldValidation::where($memberType, 'YES')->get();


        $rules = [];

        foreach ($requiredFields as $field) {
            switch ($field->input_type) {
                case 'text':
                    $rules[$field->field_key] = 'required|string|max:255';
                    break;
                case 'email':
                    $rules[$field->field_key] = 'required|email|unique:members,email';
                    break;
                case 'date':
                    $rules[$field->field_key] = 'required|date';
                    break;
                case 'tel':
                    $rules[$field->field_key] = 'required|string|min:10|max:15|unique:members,mobile_number';
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
