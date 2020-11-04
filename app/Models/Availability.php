<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = [
        'dayofweek', 'start','end', 'name','user_id'
    ];
}
