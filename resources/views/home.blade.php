@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            @if($wallets)
                <div class="container">
                    @foreach ($wallets as $wallet)
                        {{ $wallet->name }}
                    @endforeach
                </div>
            
                {{ $wallets->links() }}
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
                                    <option>{{ $currency }}</option>
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
