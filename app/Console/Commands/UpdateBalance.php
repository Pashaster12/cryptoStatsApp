<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use CryptoStat;
use Illuminate\Support\Facades\Mail;
use App\Mail\BalanceChanged;
use Exception;
use Log;

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
        $wallets = Wallet::with('latestInfo')->get();
        
        //Updating the balance values for wallet records in DB
        //and collecting the array of changed wallets sorted by user_ids
        //for sending email notifications
        $user_data = [];
        
        foreach ($wallets as $wallet) {
            //if balance of the wallet has changed or record of the balance changing hasn't existed yet, 
            //we will create a new record in the wallet_infos table
            $balance_changes = [];
            
            try {
                $balance_changes = $this->checkBalanceChanged($wallet);                
            } catch (Exception $message) {
                Log::error($message);
            }
            
            if ($balance_changes) {
                extract($balance_changes, EXTR_OVERWRITE);

                $wallet->infos()->create([
                    'balance' => $new_balance,
                ]);

                $user_data[$wallet->user->id]['user'] = $wallet->user;
                if (!is_null($last_balance)) {
                    $user_data[$wallet->user->id]['wallets'][] = [
                        'address' => $wallet->address,
                        'balance' => $new_balance,
                        'delta' => $this->formatNumber($new_balance - $last_balance)
                    ];
                }
            }
        }
        
        //sending email notifications for users about their wallets balance changes
        $this->sendNotifications($user_data);
    }
    
    /**
     * Check if the wallet balance has changed
     * 
     * @param App\Wallet $wallet
     * @return array $result
     */
    private function checkBalanceChanged($wallet)
    {
        $result = [];
        $last_balance = null;
        
        CryptoStat::setCurrency($wallet->currency);
        $new_balance = CryptoStat::getBalance($wallet->address);
        $new_balance_rounded = $this->formatNumber($new_balance);
        
        //if balance of the wallet hasn't changed, do nothing
        if (!$wallet->infos->isEmpty()) {
            $last_balance = $wallet->latestInfo->balance;
            if ($new_balance_rounded == $last_balance) return $result;
        }
        
        $result = [
            'new_balance' => $new_balance_rounded,
            'last_balance' => $last_balance
        ];

        return $result;
    }
    
    /**
     * Send notifications for the user
     * 
     * @param array $user_data
     */
    private function sendNotifications($user_data)
    {
        foreach ($user_data as $data) {
            if (isset($data['wallets'])) {
                $message = (new BalanceChanged($data['user'], $data['wallets']))
                        ->onConnection(env('QUEUE_DRIVER'))
                        ->onQueue('emails');
                
                Mail::to($data['user']->email)->queue($message);
            }
        }        
    }
    
    /**
     * Round the balance value with the rounding_degree config value
     * 
     * @param float $number
     * @return float
     */
    private function formatNumber(float $number): float
    {
        return number_format($number, config('constants.rounding_degree'), '.', '');
    }
}
