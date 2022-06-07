@extends('customer.auth.master')
@section('head-tag')
    <style>
        #resend-otp{
            font-size: 1rem;
        }
    </style>
@endsection

@section('content')

    <form action="{{ route('auth.customer.login-confirm', $token) }}"  class="form" method="post">
        @csrf

        <a class="account-logo" href="index.html">
            <img src="/img/weblogo.png" alt="">
        </a>

        <section class="login-title mb-2">
            <a href="{{ route('auth.customer.login-register-form') }}">
                <i class="fa fa-arrow-right"></i>
            </a>
        </section>

        <section class="login-title">
            کد تایید را وارد نمایید
        </section>

        @if($otp->type == 0)
            <section class="login-info">
                کد تایید برای شماره موبایل {{ $otp->login_id }} ارسال گردید
            </section>
        @else
            <section class="login-info">
                کد تایید برای ایمیل {{ $otp->login_id }} ارسال گردید
            </section>
        @endif


        <div class="form-content form-account">
            <section class="login-title mb-2"></section>

            <input type="text" class="txt txt-l @error('id') is-invalid @enderror" name="otp" autofocus autocomplete="otp" required>
            @error('otp')
            <span class="alert_required bg-danger text-white invalid-feedback" role="alert">
                 <strong>{{ $message }}</strong>
            </span>
            @enderror
            <br>
            <button type="submit" class="btn continue-btn">  تایید. </button>
            <section id="resend-otp" class="d-none">
                <a href="{{ route('auth.customer.login-resend-otp',$token) }}" class="text-decoration-none text-primary">دریافت مجدد کد تایید</a>
            </section>
            <section id="timer"></section>
        </div>

    </form>

@endsection


@section('script')

    @php
        $timer = ((new \Carbon\Carbon($otp->created_at))->addMinutes(5)->timestamp - \Carbon\Carbon::now()->timestamp) * 1000;

    @endphp

    <script>

        var countDownDate = new Date().getTime() + {{ $timer }};
        var timer = $('#timer');
        var resendOtp = $('#resend-otp');

        var x = setInterval(function(){

            var now = new Date().getTime();

            var distance = countDownDate - now;

            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            if(minutes == 0){
                timer.html('ارسال مجدد کد تایید تا ' + seconds + 'ثانیه دیگر')
            }
            else{
                timer.html('ارسال مجدد کد تایید تا ' + minutes + 'دقیقه و ' + seconds + 'ثانیه دیگر');
            }
            if(distance < 0)
            {
                clearInterval(x);
                timer.addClass('d-none');
                resendOtp.removeClass('d-none');
            }

        }, 1000)
    </script>
@endsection
