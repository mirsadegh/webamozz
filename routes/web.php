

<?php

use App\Http\Controllers\Auth\Customer\LoginRegisterController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', function () {
    return view('index');

});

Route::namespace('Auth')->middleware('guest')->group(function(){

      Route::get('login-register',[LoginRegisterController::class,'loginRegisterForm'])->name('auth.customer.login-register-form');
      Route::middleware('throttle:customer-login-register-limiter')->post('login-register',[LoginRegisterController::class,'loginRegister'])->name('auth.customer.login-register');

      Route::get('login-confirm/{token}',[LoginRegisterController::class,'loginConfirmForm'])->name('auth.customer.login-confirm-form');
      Route::middleware('throttle:customer-login-confirm-limiter')->post('login-confirm/{token}',[LoginRegisterController::class,'loginConfirm'])->name('auth.customer.login-confirm');
      Route::middleware('throttle:customer-login-resend-otp-limiter')->get('login-resend-otp/{token}',[LoginRegisterController::class,'loginResendOtp'])->name('auth.customer.login-resend-otp');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
