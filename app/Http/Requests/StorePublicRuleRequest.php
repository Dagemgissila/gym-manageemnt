<?php

namespace App\Http\Requests;

use App\Enums\ActiveInactive;
use Illuminate\Foundation\Http\FormRequest;

class StorePublicRuleRequest extends FormRequest
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
            "setting_rule" => ["required", "string", "max:255", "unique:public_rules,setting_rule"],
            "setting_value" => ["required", "string", "max:255"],
            "status" => ["required", "boolean"] // Fixed in rule
        ];
    }

}
