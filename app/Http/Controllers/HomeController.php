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
                ->paginate(config('constants.wallets_per_page'));

        if (!$wallets->isEmpty()) {
            foreach ($wallets as $key => $wallet) {
                if ($wallet) {
                    //Building the block explorer link to the wallet address without DB saving
                    CryptoStat::setCurrency($wallet['currency']);
                    $wallets[$key]['block_explorer_link'] = CryptoStat::getBlockExplorerLink($wallet['address']);

                    //Fill the balance and date of balance updating with blank 
                    //if there are no balance updating records in the walloe_infos table yet.
                    //For others just create the specialized attribute in the wallet array                                       
                    if (!$wallet->infos->isEmpty()) {
                        $created_at = $wallet->infos->last()->created_at;
                        $balance = $wallet->infos->last()->balance;

                        //Round the balance value with the rounding_degree param
                        $wallets[$key]['last_balance'] = number_format($balance, config('constants.rounding_degree'), '.', '');
                        $wallets[$key]['last_balance_updating'] = $created_at;
                    } else
                        $wallets[$key]['last_balance_updating'] = $wallets[$key]['last_balance'] = 'N/A';
                }
            }
        }

        $data['wallets'] = $wallets;

        return view('home', $data);
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function addWallet(Request $request)
    {
        if ($request->post()) {
            $request->validate([
                'currency' => 'required|string|max:4',
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