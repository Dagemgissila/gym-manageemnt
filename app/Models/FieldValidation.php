<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldValidation extends Model
{
    //
    protected $fillable = [
        'field_name',
        'field_key',
        'prospect',
        'trial',
        'membership',
        'group',
        'input_type'
    ];

}
