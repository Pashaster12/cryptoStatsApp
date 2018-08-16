<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use CryptoStat;
use Illuminate\Support\Facades\Mail;
use App\Mail\BalanceChanged;

use App\Wallet;

class UpdateBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updatebalance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating the cryptocurrency wallets balance';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $wallets = Wallet::with('infos')->get();
        if (!$wallets->isEmpty())
        {
            $user_data = [];
            foreach ($wallets as $wallet)
            {
                $last_balance = 0;
                
                $new_balance = CryptoStat::getBalance($wallet->currency, $wallet->address);
                if (!$wallet->infos->isEmpty())
                {
                    $last_balance = $wallet->infos->last()->balance;
                    if ($new_balance == $last_balance) continue;
                }
                
                $saved = $wallet->infos()->create([
                    'balance' => $new_balance,
                ]);
                
                if($saved)
                {
                    $user_data[$wallet->user->id]['user'] = $wallet->user;                    
                    $user_data[$wallet->user->id]['wallets'][] = [
                        'address' => $wallet->address,
                        'balance' => $new_balance,
                        'delta' => number_format($new_balance - $last_balance, 8)
                    ];
                }
            }
            
            if($user_data)
            {
                foreach($user_data as $data)
                {
                    $message = (new BalanceChanged($data['user'], $data['wallets']));

                    Mail::to($data['user']->email)
                            ->queue($message);
                }
            }
        }
    }
}
