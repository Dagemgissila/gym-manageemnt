<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Change to true to allow validation
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($this->user->id),
            ],
            'mobile_number' => ['sometimes', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', 'min:6'],
            'gender' => ['sometimes', 'in:Male,Female,Other'],
            'date_of_birth' => ['sometimes', 'date'],
            'address' => ['sometimes', 'string', 'max:500'],
            'city' => ['sometimes', 'string', 'max:255'],
            'emergency_contact_name' => ['sometimes', 'string', 'max:255'],
            'emergency_contact_phone' => ['sometimes', 'string', 'max:20'],
            'hire_date' => ['sometimes', 'date'],
            'salary' => ['sometimes', 'numeric', 'min:0'],
            'assigned_location' => ['sometimes', 'string', 'max:255'],
            'role' => ['sometimes', 'exists:roles,name'],
        ];
    }
}
