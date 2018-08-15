<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletInfo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'wallet_id', 'balance'
    ];
    
    public $timestamps = false;
}
