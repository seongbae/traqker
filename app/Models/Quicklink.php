<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quicklink extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'url', 'user_id', 'order', 'model_id'
    ];

    public function getPathAttribute()
    {
        return substr(parse_url($this->url, PHP_URL_PATH), 1);
    }
}
