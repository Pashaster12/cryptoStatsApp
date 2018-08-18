@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(!$wallets->isEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered bg-white text-center">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Block explorer link</th>
                                <th scope="col">Latest balance</th>
                                <th scope="col">Wallet creation date</th>
                                <th scope="col">Wallet balance changing date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($wallets as $key => $wallet)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td><a href="{{ $wallet['block_explorer_link'] }}" title="Press for new tab opening" target="_blank" rel="nofollow noindex">{{ $wallet['address'] }}</a></td>
                                    <td>{{ $wallet['last_balance'] }}</td>
                                    <td>{{ $wallet['created_at'] }}</td>
                                    <td>{{ $wallet['last_balance_updating'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{ $wallets->links() }}
            @endif
        </div>
            
        <div class="col-md-6">                
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $error }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endforeach
            @endif
            
            <div class="card">
                <div class="card-header">Add new crypto wallet</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('/addWallet') }}">
                        @csrf
                        
                        <div class="form-group">
                            <label for="currency">Wallet cryptocurrency</label>
                            <select class="form-control" name="currency" id="currency">
                                @foreach($currencies as $currency)
                                    <option {{ (old("currency") == $currency ? "selected":"") }}>{{ $currency }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Wallet address</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" placeholder="Please, enter the cryptocurrency wallet address">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Add wallet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
