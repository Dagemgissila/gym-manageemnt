<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldContent extends Model
{
    //


    public function variableFields()
    {
        return $this->hasMany(VariableFields::class, "field_content_id");
    }
}
