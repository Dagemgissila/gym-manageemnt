<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreForeignCurrencyRequest extends FormRequest
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
            "code" => "required|string|unique:foreign_currencies,code",
            "name" => "required|string|unique:foreign_currencies,name",
            "symbol" => "required|string|unique:foreign_currencies,symbol",
            "decimal_place" => "required|integer"
        ];
    }
}
