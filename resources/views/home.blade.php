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
                            <label for="currency">Example select</label>
                            <select class="form-control" name="currency" id="currency">
                                @foreach($currencies as $currency)
                                    <option>{{ $currency }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="wallet">Another label</label>
                            <input type="text" class="form-control" id="wallet" name="wallet" value="{{ old('wallet') }}" placeholder="Cryptocurrency wallet number">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Add wallet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
