<?php
namespace Sadegh\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

use Sadegh\User\Http\Requests\Auth\LoginRegisterRequest;
use Sadegh\User\Http\Services\Message\Email\EmailService;
use Sadegh\User\Http\Services\Message\MessageService;
use Sadegh\User\Http\Services\Message\SMS\SmsService;
use Sadegh\User\Models\Otp;
use Sadegh\User\Models\User;
use Sadegh\User\Repositories\UserRepo;
use Session;


class LoginRegisterController extends Controller
{
    private $userRepo;
    public function __construct(UserRepo $userRepo)
    {
          $this->userRepo = $userRepo;
    }

    public function loginRegisterForm()
    {
        return view('User::Front.login-register');
    }

    public function loginRegister(LoginRegisterRequest $request)
    {

          $inputs = $request->all();
          $emailOrMobile = $inputs['id'];
         //check id is email or not
          if(is_array($this->checkEmailOrMobile($emailOrMobile))){
              list($type,$user) = $this->checkEmailOrMobile($emailOrMobile);
              //create otp codes
              list($otpCode, $token) = $this->createOtpCodes($user, $emailOrMobile, $type);
              //send sms or email
              $messagesService = $this->sendSmsOrEmail($type, $user, $otpCode, $emailOrMobile);
              $messagesService->send();
              return redirect()->route('auth.login-confirm-form', $token);
          }else{
              $errorText = 'شناسه ورودی شما نه شماره موبایل است نه ایمیل';
              return redirect()->route('auth.login-register-form')->withErrors(['id' => $errorText]);
          }
    }

    public function loginConfirmForm($token)
    {
        $otp = $this->userRepo->findOtpByToken($token);
        if(empty($otp))
        {
            return redirect()->route('auth.login-register-form')->withErrors(['id' => 'آدرس وارد شده نامعتبر میباشد']);
        }
        return view('User::Front.login-confirm', compact('token', 'otp'));
    }


    public function loginConfirm($token,LoginRegisterRequest $request)
    {
        $inputs = $request->all();
        $otp = $this->userRepo->findOtpByTokenUsedTime($token);
        if(empty($otp))
        {
            return redirect()->route('auth.login-register-form', $token)->withErrors(['id' => 'آدرس وارد شده نامعتبر میباشد']);
        }

        //if otp not match
        if($otp->otp_code !== $inputs['otp'])
        {
            return redirect()->route('auth.login-confirm-form', $token)->withErrors(['otp' => 'کد وارد شده صحیح نمیباشد']);
        }

        // if everything is ok :
        $otp->update(['used' => 1]);
        $user = $otp->user()->first();
        if($otp->type == 0 && empty($user->mobile_verified_at))
        {
            $user->update(['mobile_verified_at' => Carbon::now()]);
        }
        elseif($otp->type == 1 && empty($user->email_verified_at))
        {
            $user->update(['email_verified_at' => Carbon::now()]);
        }
        Auth::login($user);
        return redirect('/');
    }

    public function loginResendOtp($token)
    {
        $otp = $this->userRepo->findOtpByTokenforResend($token);
        if(empty($otp)){
            return redirect()->route('auth.login-register-form',$token)->withErrors(['id' => 'ادرس وارد شده نامعتبر است']);
        }

        $user = $otp->user()->first();
        //create otp code
        $id = $otp->login_id;
        $type = $otp->type;
        list($otpCode, $token) = $this->createOtpCodes($user, $id, $type);

        //send sms or email
        $messagesService = $this->sendSmsOrEmail($type, $user, $otpCode, $id);
        $messagesService->send();
        return redirect()->route('auth.login-confirm-form', $token);
    }


    /**
     * @param $emailOrMobile
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function checkEmailOrMobile($emailOrMobile)
    {
        if(filter_var($emailOrMobile,FILTER_VALIDATE_EMAIL))
        {
            $type = 1 ; // 1 =>email
            $user = $this->userRepo->findUserByEmailOrCreateUser($emailOrMobile);
            return array($type,$user);
        }elseif(preg_match('/^(\+98|98|0)9\d{9}$/',$emailOrMobile)){
            $type = 0 ; // 0 => mobile
            // all mobile numbers are in on format 9** *** ***
            $emailOrMobile = ltrim($emailOrMobile,0);
            $emailOrMobile = substr($emailOrMobile,0,2) === '98' ? substr($emailOrMobile,2) : $emailOrMobile;
            $emailOrMobile = str_replace('+98','',$emailOrMobile);
            $user = $this->userRepo->findUserByMobileOrCreate($emailOrMobile);
            return array($type,$user);
        }
    }


    /**
     * @param $user
     * @param $id
     * @param int $type
     * @return array
     */
    public function createOtpCodes($user, $id, int $type): array
    {
        $otpCode = rand(111111, 999999);
        $token = Str::random(60);
        $otpInputs = [
            'token' => $token,
            'user_id' => $user->id,
            'otp_code' => $otpCode,
            'login_id' => $id,
            'type' => $type,
        ];
        $this->userRepo->otpCreate($otpInputs);
        return array($otpCode, $token);
    }

    /**
     * @param int $type
     * @param $user
     * @param mixed $otpCode
     * @param $id
     * @return MessageService
     */
    public function sendSmsOrEmail(int $type, $user, mixed $otpCode, $id): MessageService
    {
        if ($type === 0) {
            //send sms
            $smsService = new SmsService();
            $smsService->setFrom(Config::get('sms.otp_from'));
            $smsService->setTo(['0' . $user->mobile]);
            $smsService->setText("مجموعه آمازون \n  کد تایید : $otpCode");
            $smsService->setIsFlash(true);

            $messagesService = new MessageService($smsService);
        } elseif ($type === 1) {
            $emailService = new EmailService();
            $details = [
                'title' => 'ایمیل فعال سازی',
                'body' => "کد فعال سازی شما : $otpCode"
            ];
            $emailService->setDetails($details);
            $emailService->setFrom('noreply@example.com', 'example');
            $emailService->setSubject('کد احراز هویت');
            $emailService->setTo($id);

            $messagesService = new MessageService($emailService);
        }
        return $messagesService;
    }

    public function logout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return  redirect('/login-register');
    }


}
