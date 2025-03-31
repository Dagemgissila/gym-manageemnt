<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseCurrency extends Model
{
    protected $fillable = ['code', 'name', 'symbol', 'decimal_place'];
}
