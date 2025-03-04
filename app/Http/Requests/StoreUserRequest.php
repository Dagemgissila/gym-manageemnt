<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Change this to true to allow the request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name'             => 'required|string|max:255',
            'last_name'              => 'required|string|max:255',
            'email'                  => 'required|email|unique:users,email',
            'mobile_number'          => 'required|string|max:15|unique:users,mobile_number',
            'password'               => 'required|string|min:8|confirmed', // Must match password_confirmation
            'profile_picture'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max size
            'gender'                 => 'required|in:Male,Female',
            'date_of_birth'          => 'required|date|before:today',
            'address'                => 'required|string|max:500',
            'city'                   => 'required|string|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone'=> 'required|string|max:15',
            'hire_date'              => 'required|date',
            'salary'                 => 'required|numeric|min:0',
            'assigned_location'      => 'required|string|max:255',
            'role'                   => 'required|exists:roles,name', // Ensure role exists in roles table
        ];
    }

    /**
     * Custom error messages (optional).
     */
    public function messages()
    {
        return [
            'email.required' => 'The email address is required.',
            'email.unique' => 'This email is already taken.',
            'password.confirmed' => 'Passwords do not match.',
            'date_of_birth.before' => 'Date of birth must be before today.',
            'role.exists' => 'The selected role is invalid.',
        ];
    }
}
