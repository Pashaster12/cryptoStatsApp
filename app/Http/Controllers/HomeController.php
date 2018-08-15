<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\IsValidAddress;
use CryptoStat;

use App\Wallet;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['currencies'] = CryptoStat::getCurrencies();
        $wallets = Wallet::with('infos')
                ->orderByDesc('created_at')
                ->paginate(20);
        
        if($wallets)
        {
            foreach($wallets as $key => $wallet)
            {
                if($wallet)
                {
                    //Building the block explorer link to the wallet address without DB saving
                    $prefix = '';
                    switch ($wallet['currency'])
                    {
                        case 'ETH':
                            $prefix = 'https://etherscan.io/address/';
                            break;
                        case 'BTC':
                        case 'LTC':
                            $prefix = 'https://chain.so/address/' . $wallet['currency'] . '/';
                            break;
                    }
                    
                    $wallets[$key]['block_explorer_link'] = $prefix . $wallet['address'];
                    
                    //Fill the balance and date of balance updating with blank 
                    //if there are no balance updating records in the walloe_infos table yet.
                    //For others just create the specialized attribute in the wallet array
                    if(!$wallet->infos->isEmpty())
                    {                        
                        $wallets[$key]['last_balance_updating'] = $wallet->infos->last()->created_at;
                        $wallets[$key]['last_balance'] = $wallet->infos->last()->balance;
                    }
                    else $wallets[$key]['last_balance_updating'] = $wallets[$key]['last_balance'] = 'N/A';
                }
            }
        }
        
        $data['wallets'] = $wallets;
        
        return view('home', $data);
    }
    
    public function addWallet(Request $request)
    {
        if ($request->post())
        {
            $request->validate([
                'currency' => 'required|string|max:3',
                'address' => ['required', 'unique:wallets', new IsValidAddress($request->currency)],
            ]);
            
            $flight = new Wallet;
            $flight->user_id = Auth::id();
            $flight->currency = $request->currency;
            $flight->address = $request->address;
            $inserted = $flight->save();
            
            $result = $inserted ? redirect('/')->with('status', 'Wallet was successfully added!') : redirect('/')->withErrors('Wallet was not added!');
            
            return $result;
        }
    }

}
