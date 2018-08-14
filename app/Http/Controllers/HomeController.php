<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LaravelCryptoStats\LaravelCryptoStatsFacade;

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
        $data['currencies'] = LaravelCryptoStatsFacade::getCurrencies();
        $data['wallets'] = [];
        
        return view('home', $data);
    }
    
    public function addWallet(Request $request)
    {
        if ($request->post())
        {
            /*$request->validate([
                'currency' => 'required|string|max:3',
                'wallet' => 'required|unique:wallets',
            ]);*/
            
            $a = LaravelCryptoStatsFacade::validateWallet($request->currency, $request->wallet);
            dd($a);
            
            return view('home');
        }
    }

}
