<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateForeignCurrencyRequest extends FormRequest
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
            "code" => ["required", "string", Rule::unique('foreign_currencies', 'code')->ignore($this->id)],
            "name" => ["required", "string", Rule::unique('foreign_currencies', 'name')->ignore($this->id)],
            "symbol" => ["required", "string", Rule::unique('foreign_currencies', 'symbol')->ignore($this->id)],
            "decimal_place" => ["required", "integer"]
        ];
    }
}
