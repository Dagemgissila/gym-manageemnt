<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DateTimeRestriction extends Model
{

    protected $fillable = ['membership_item_id', 'day', 'from_time', 'to_time', 'time_period'];
    public function MembershipItem()
    {
        return $this->belongsTo(MembershipItem::class, 'membership_item_id');
    }
}
