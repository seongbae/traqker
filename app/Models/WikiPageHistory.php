<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WikiPageHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'wiki_page_id',
        'content',
        'user_id',
        'change_description'
    ];
}
