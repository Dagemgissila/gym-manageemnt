<?php

namespace App\Http\Requests;

use App\Enums\ActiveInactive;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePublicRuleRequest extends FormRequest
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
            "setting_rule" => [
                "required",
                "string",
                "max:255",
                Rule::unique('public_rules')->ignore($this->route('public_rule')->id) // Ignore the current record
            ],
            "setting_value" => ["required", "string", "max:255"],
            "status" => ["required", "boolean"]
        ];
    }

}
