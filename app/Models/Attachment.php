<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'filename', 'label', 'size','attached_model', 'attached_model_id'
    ];

    public function attachable()
    {
        return $this->morphTo('attachments', 'attached_model', 'attached_model_id');;
    }
}
