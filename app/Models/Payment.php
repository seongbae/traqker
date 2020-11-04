<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Payment extends Model
{

	protected $fillable = [
        'payee_id', 'payer_id','method', 'amount','transaction_id','external_transaction_id'
    ];


    public function payer()
    {
        return $this->hasOne(User::class,'id', 'payer_id');
    }

    public function payee()
    {
        return $this->hasOne(User::class,'id', 'payee_id');
    }
}
