<?php

namespace Sadegh\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;


class LoginRegisterTest extends TestCase
{
        use RefreshDatabase;

        /**
         *
         * @return void
         */

    public function test_user_can_see_loginRegister_form()
    {
         $response = $this->get(route('auth.login-register-form'));
         $response->assertStatus(200)
                  ->assertViewIs('User::Front.login-register');
    }
    public function test_validation_request_required_email_or_mobile()
    {
//          $this->withoutExceptionHandling();
          Session::start();
          $data = [ '_token' => csrf_token(), 'id' => ''];
          $errors = [
              'id' => 'فیلد ایمیل یا شماره موبایل الزامی است.'
          ];
           $this->post(route('auth.login-register'),$data)
           ->assertSessionHasErrors($errors);
    }

    public function test_validation_request_minimum_email_or_mobile()
    {
//          $this->withoutExceptionHandling();
          Session::start();
          $data = [ '_token' => csrf_token(), 'id' => 'sdfg2345'];
          $errors = [
              'id' => 'ایمیل یا شماره موبایل نباید کمتر از 11 کاراکتر باشد.'
          ];
           $this->post(route('auth.login-register'),$data)
           ->assertSessionHasErrors($errors);
    }

    public function test_validation_request_maximum_email_or_mobile()
    {
//          $this->withoutExceptionHandling();
          Session::start();
          $data = [ '_token' => csrf_token(), 'id' => 'wertyusdfghjsdfghjdfghjsdfdfghjfghjdfghjdfghjfghjfghjkdfghjsdfghjghsdfghjxcvbnxcvbnsdfghsdfghdfghsdfghdfghjdfghjdfghdfghjdfghsdfghdsdfghjsdfghjsdfghjwertyxcvbzxcvbnfghjwertye@gmail.rty34567567dfghjsdfg2345'];
          $errors = [
              'id' => 'ایمیل یا شماره موبایل نباید بیشتر از 64 کاراکتر باشد.'
          ];
           $this->post(route('auth.login-register'),$data)
           ->assertSessionHasErrors($errors);
    }

    public function test_id_dont_mobile_or_email()
    {
//        $this->withoutExceptionHandling();
        Session::start();
        $data = ['_token' => csrf_token() , 'id' => 'sdfghjdfghjdfghfgh'];

        $this->post(route('auth.login-register'),$data)
            ->assertRedirect(route('auth.login-register-form'));
    }

    public function test_login_confirm_form_send_token()
    {
          //
    }


}
