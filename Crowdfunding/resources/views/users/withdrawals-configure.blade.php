@extends('app')

@section('title') {{ trans('misc.withdrawals') }} {{ trans('misc.configure') }} - @endsection

@section('content')
<div class="jumbotron mb-0 bg-sections text-center">
    <div class="container position-relative">
        <h1>{{ trans('misc.withdrawals') }} {{ trans('misc.configure') }}</h1>
        <p class="mb-0">
            <strong>{{ trans('misc.default_withdrawal') }}</strong>: @if (auth()->user()->payment_gateway == '') {{ trans('misc.unconfigured') }} @else {{ auth()->user()->payment_gateway }} @endif
        </p>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('users.navbar-settings')
        </div>

        <div class="col-md-9">
            @include('errors.errors-forms')

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <h6 class="mb-2">Choose your withdraw option and save</h6>
             <!-- ***** FORM ***** -->
       <form action="{{ url('withdrawals/configure/paypal') }}" method="post" class="mb-5">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="form-floating mb-3">
         <input type="email" class="form-control" id="input-paypal" value="{{auth()->user()->paypal_account}}" name="email_paypal" placeholder="{{ trans('misc.email_paypal') }}">
         <label for="input-paypal">{{ trans('admin.paypal_account') }}</label>
       </div>

       <div class="form-floating mb-3">
        <input type="email" class="form-control" id="input-confirm_email" name="email_paypal_confirmation" placeholder="{{ trans('misc.confirm_email') }}">
        <label for="input-confirm_email">{{ trans('misc.confirm_email') }}</label>
      </div>

       <button type="submit" class="btn w-100 btn-lg btn-primary no-hover">{{ trans('admin.save') }}</button>
   </form><!-- ***** END FORM ***** -->

            <h6 class="mb-2">Bank Transfer</h6>
            <form action="{{ url('withdrawals/configure/bank') }}" method="post">
                @csrf
                <textarea class="form-control mb-3" name="bank" placeholder="{{ trans('misc.bank_details') }}">{{ auth()->user()->bank }}</textarea>
                <button type="submit" class="btn btn-primary">{{ trans('admin.save') }}</button>
            </form>

            <h6 class="mb-2">Zelle</h6>
            <form action="{{ url('withdrawals/configure/zelle') }}" method="post">
                @csrf
                <input type="text" class="form-control mb-3" name="zelle_name" value="{{ old('zelle_name', json_decode(auth()->user()->zelle)->zelle_name ?? '') }}" placeholder="Full Name">
                <input type="text" class="form-control mb-3" name="zelle_contact" value="{{ old('zelle_contact', json_decode(auth()->user()->zelle)->zelle_contact ?? '') }}" placeholder="Email or Phone">
                <button type="submit" class="btn btn-primary">{{ trans('admin.save') }}</button>
            </form>

            <h6 class="mb-2">Venmo</h6>
            <form action="{{ url('withdrawals/configure/venmo') }}" method="post">
                @csrf
                <input type="text" class="form-control mb-3" name="venmo_name" value="{{ old('venmo_name', json_decode(auth()->user()->venmo)->name ?? '') }}" placeholder="Venmo Name">
                <input type="text" class="form-control mb-3" name="venmo_phone" value="{{ old('venmo_phone', json_decode(auth()->user()->venmo)->phone ?? '') }}" placeholder="Phone Number">
                <button type="submit" class="btn btn-primary">{{ trans('admin.save') }}</button>
            </form>
            <h6 class="mb-2">International Bank Transfer</h6>
            <form action="{{ url('withdrawals/configure/international_bank') }}" method="post">
                @csrf
                <textarea class="form-control mb-3" name="international_bank" >{{ auth()->user()->international_bank }}</textarea>
                <button type="submit" class="btn btn-primary">{{ trans('admin.save') }}</button>
            </form>

            <h6 class="mb-2">Apple Pay</h6>
            <form action="{{ url('withdrawals/configure/applepay') }}" method="post">
                @csrf
                <input type="text" class="form-control mb-3" name="apple_name" value="{{ old('apple_name', json_decode(auth()->user()->apple_pay)->apple_name ?? '') }}" placeholder="Full Name">
                <input type="text" class="form-control mb-3" name="apple_id" value="{{ old('apple_id', json_decode(auth()->user()->apple_pay)->apple_id ?? '') }}" placeholder="Apple Pay ID">
                <button type="submit" class="btn btn-primary">{{ trans('admin.save') }}</button>
            </form>

            <h6 class="mb-2">Crypto</h6>
            <form action="{{ url('withdrawals/configure/crypto') }}" method="post">
                @csrf
                <select class="form-control mb-3" name="crypto_type">
                    <option value="bitcoin" {{ old('crypto_type', json_decode(auth()->user()->crypto_wallet)->crypto_type ?? '') == 'bitcoin' ? 'selected' : '' }}>Bitcoin</option>
                    <option value="usdt" {{ old('crypto_type', json_decode(auth()->user()->crypto_wallet)->crypto_type ?? '') == 'usdt' ? 'selected' : '' }}>USDT</option>
                    <option value="doge" {{ old('crypto_type', json_decode(auth()->user()->crypto_wallet)->crypto_type ?? '') == 'doge' ? 'selected' : '' }}>Dodge</option>
                    <option value="paypal_usd" {{ old('crypto_type', json_decode(auth()->user()->crypto_wallet)->crypto_type ?? '') == 'paypal_usd' ? 'selected' : '' }}>PayPal-USD</option>
                    <option value="ethereum" {{ old('crypto_type', json_decode(auth()->user()->crypto_wallet)->crypto_type ?? '') == 'ethereum' ? 'selected' : '' }}>Ethereum</option>
                    <option value="ton" {{ old('crypto_type', json_decode(auth()->user()->crypto_wallet)->crypto_type ?? '') == 'ton' ? 'selected' : '' }}>TON</option>
                    <option value="not" {{ old('crypto_type', json_decode(auth()->user()->crypto_wallet)->crypto_type ?? '') == 'not' ? 'selected' : '' }}>NOT</option>
                    <option value="bnb" {{ old('crypto_type', json_decode(auth()->user()->crypto_wallet)->crypto_type ?? '') == 'bnb' ? 'selected' : '' }}>BNB</option>
                    <option value="usdc" {{ old('crypto_type', json_decode(auth()->user()->crypto_wallet)->crypto_type ?? '') == 'usdc' ? 'selected' : '' }}>USD Coin - USDC</option>
                    <option value="ada" {{ old('crypto_type', json_decode(auth()->user()->crypto_wallet)->crypto_type ?? '') == 'ada' ? 'selected' : '' }}>Cardano - ADA</option>
                </select>
                <input type="text" class="form-control mb-3" name="crypto_network" value="{{ old('crypto_network', json_decode(auth()->user()->crypto_wallet)->crypto_network ?? '') }}" placeholder="Enter Network">
                <input type="text" class="form-control mb-3" name="crypto_wallet" value="{{ old('crypto_wallet', json_decode(auth()->user()->crypto_wallet)->crypto_wallet ?? '') }}" placeholder="Enter Wallet Address">
                <button type="submit" class="btn btn-primary">{{ trans('admin.save') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
