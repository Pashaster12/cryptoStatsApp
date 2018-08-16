<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use CryptoStat;

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
            foreach ($wallets as $wallet)
            {
                $new_balance = CryptoStat::getBalance($wallet->currency, $wallet->address);
                if (!$wallet->infos->isEmpty())
                {
                    $last_balance = $wallet->infos->last()->balance;
                    if ($new_balance != $last_balance)
                    {
                        $wallet->infos()->create([
                            'balance' => $new_balance,
                        ]);
                    }
                } 
                else
                {
                    $wallet->infos()->create([
                        'balance' => $new_balance,
                    ]);
                }
            }
        }
    }
}
