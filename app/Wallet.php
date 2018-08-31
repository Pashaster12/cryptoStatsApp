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
        'user_id', 'address', 'currency'
    ];
    
    public $timestamps = false;
    
    /**
     * The wallet_infos that belong to the wallet.
     */
    public function infos()
    {
        return $this->hasMany('App\WalletInfo');
    }
    
    /**
     * Get the user that owns the wallet.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function latestInfo()
    {
        return $this->hasOne('App\WalletInfo')->latest();
    }
}
