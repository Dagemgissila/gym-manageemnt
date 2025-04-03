<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherRequest extends FormRequest
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
            "member_id" => ["required", "exists:members,id"],
            "amount" => ["required", "numeric", "gt:0"], // Allows integers and decimals
            "validity" => ["required", "date", "after_or_equal:today"], // Ensures validity is today or later
            "comment" => ["required", "string"]
        ];
    }

}
