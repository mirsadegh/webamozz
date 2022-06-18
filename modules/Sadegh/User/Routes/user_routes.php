<?php

use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Sadegh\User\Http\Controllers\Auth\LoginRegisterController;

Route::group(['middleware' => 'web'],function(){

    Route::group(['middleware' => 'guest'],function (){
        Route::get('login-register',[LoginRegisterController::class,'loginRegisterForm'])->name('auth.login-register-form');
        Route::middleware('throttle:customer-login-register-limiter')->post('login-register',[LoginRegisterController::class,'loginRegister'])->name('auth.login-register');

        Route::get('login-confirm/{token}',[LoginRegisterController::class,'loginConfirmForm'])->name('auth.login-confirm-form');
        Route::middleware('throttle:customer-login-confirm-limiter')->post('login-confirm/{token}',[LoginRegisterController::class,'loginConfirm'])->name('auth.login-confirm');
        Route::middleware('throttle:customer-login-resend-otp-limiter')->get('login-resend-otp/{token}',[LoginRegisterController::class,'loginResendOtp'])->name('auth.login-resend-otp');
    });
Route::post('/logout',[LoginRegisterController::class,'logout'])->name('logout');

});
