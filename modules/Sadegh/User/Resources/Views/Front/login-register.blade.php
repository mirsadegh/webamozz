@extends('User::Front.master')
@section('content')

<form action="{{ route('auth.login-register') }}" class="form" method="post">
    @csrf

    <a class="account-logo" href="index.html">
        <img src="/img/weblogo.png" alt="">
    </a>

    <div class="form-content form-account">
        <section class="login-title mb-2">ورود / ثبت نام</section>
        <section class="login-info my-1">شماره موبایل یا پست الکترونیک خود را وارد کنید</section>
        <input type="text" class="txt txt-l @error('id') is-invalid   @enderror" name="id" autofocus autocomplete="id" required>
        @error('id')
            <span class="alert_required bg-danger text-white invalid-feedback" role="alert">
                 <strong>{{ $message }}</strong>
            </span>
        @enderror
        <br>
        <button type="submit" class="btn continue-btn">ثبت نام و ادامه</button>

    </div>

</form>

@endsection


