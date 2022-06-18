<?php

namespace Sadegh\User\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class LoginRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $route = Route::current();
        if($route->getName() == 'auth.login-register')
        {
            return [
                'id' => 'required|min:11|max:64|regex:/^[a-zA-Z0-9_.@\+]*$/',
            ];
        }
        elseif($route->getName() == 'auth.login-confirm')
        {
            return [
                'otp' => 'required|min:6|max:6',
            ];
        }
    }

    public function attributes()
    {
        return [
               'id' => 'ایمیل یا شماره موبایل'
        ];
    }
}
