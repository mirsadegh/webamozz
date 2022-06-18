<?php

namespace Sadegh\User\Repositories;

use Carbon\Carbon;
use Sadegh\User\Models\Otp;
use Sadegh\User\Models\User;

class UserRepo
{
    public function findOtpByToken($token)
    {
        return Otp::where('token', $token)->first();
    }

    public function findOtpByTokenUsedTime($token)
    {
       return Otp::where('token', $token)->where('used', 0)->where('created_at', '>=', Carbon::now()->subMinute(5)->toDateTimeString())->first();
    }

    public function findOtpByTokenforResend($token)
    {
       return Otp::where('token',$token)->where('created_at','<=',Carbon::now()->subMinutes(5)->toDateTimeString())->first();
    }

    public function findUserByEmailOrCreateUser($emailOrMobile)
    {
      $user =  User::where('email',$emailOrMobile)->firstOrCreate(
            ['email'      => $emailOrMobile],
            ['password'   => '98355154'],
            ['activation' => 1],
        );
      return $user;
    }

    public function findUserByMobileOrCreate($emailOrMobile)
    {
       $user =  User::where('mobile',$emailOrMobile)->firstOrCreate(
            ['mobile'      => $emailOrMobile],
            ['password'   => '98355154'],
            ['activation' => 1],
        );
       return $user;
    }

    public function otpCreate($otpInputs)
    {
        Otp::create($otpInputs);
    }
}
