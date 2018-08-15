<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address'
    ];
    
    public $timestamps = false;
    
    /**
     * The wallet_infos that belong to the wallet.
     */
    public function infos()
    {
        return $this->hasMany('App\WalletInfo');
    }
}
