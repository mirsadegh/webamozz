@extends('User::Front.master')
@section('head-tag')
    <style>
        #resend-otp {
            font-size: 1rem;
        }
        .login-info{
            text-align: center;
        }
        #timer{
            font-size: 10px;
            color: #c4c4c4;
        }
    </style>
@endsection

@section('content')

    <form action="{{ route('auth.login-confirm', $token) }}" class="form" method="post">
        @csrf

        <a class="account-logo" href="index.html">
            <img src="/img/weblogo.png" alt="">
        </a>

        <section class="login-title mb-2">
            <a href="{{ route('auth.login-register-form') }}">
                <i class="fa fa-arrow-right"></i>
            </a>
        </section>

        <section class="login-title">
                   کد تایید را وارد نمایید.
        </section>
        @if($otp->type == 0)
            <section class="login-info">
                <span>کد تایید برای شماره موبایل زیر ارسال گردید.</span>
                <span>{{ $otp->login_id }}</span>
            </section>
        @else
            <section class="login-info">
                <span>کد تایید برای ایمیل زیر ارسال گردید.</span>
                <span>{{ $otp->login_id }}</span>
            </section>
        @endif
        <div class="form-content form-content1">
            <section class="login-title mb-2"></section>
            <input class="activation-code-input @error('id') is-invalid @enderror" name="otp" placeholder="فعال سازی" autofocus required>
            @error('otp')
            <span class="alert_required bg-danger text-white invalid-feedback" role="alert">
                 <strong>{{ $message }}</strong>
            </span>
            @enderror
            <br>
            <button type="submit" class="btn continue-btn"> تایید.</button>
            <section id="resend-otp" class="d-none">
                <a href="{{ route('auth.login-resend-otp',$token) }}"
                   class="text-decoration-none text-primary">دریافت مجدد کد تایید</a>
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

        var x = setInterval(function () {

            var now = new Date().getTime();

            var distance = countDownDate - now;

            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            if (minutes == 0) {
                timer.html('ارسال مجدد کد تایید تا ' + seconds + 'ثانیه دیگر')
            } else {
                timer.html('ارسال مجدد کد تایید تا ' + minutes + 'دقیقه و ' + seconds + 'ثانیه دیگر');
            }
            if (distance < 0) {
                clearInterval(x);
                timer.addClass('d-none');
                resendOtp.removeClass('d-none');
            }
        }, 1000)
    </script>
    <script src="{{ asset('js/activation-code.js') }}"></script>
@endsection
