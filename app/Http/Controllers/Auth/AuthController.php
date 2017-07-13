<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use LaravelCaptcha\Integration\BotDetectCaptcha;
use App\AllowedAdmins;

class AuthController extends Controller
{
    protected $redirectPath = '/accounts';
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }


    private function getExampleCaptchaInstance()
    {
        // Captcha parameters
        $captchaConfig = [
            'CaptchaId' => 'ExampleCaptcha', // a unique Id for the Captcha instance
            'UserInputId' => 'CaptchaCode', // Id of the Captcha code input textbox
            // The path of the Captcha config file is inside the config folder
            'CaptchaConfigFilePath' => 'captcha_config/ExampleCaptchaConfig.php'
        ];
        return BotDetectCaptcha::GetCaptchaInstance($captchaConfig);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'g-recaptcha-response' => 'required|captcha',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $allUsers = AllowedAdmins::lists('email')->toArray();
        if (in_array($_POST['email'], $allUsers)) {
            $admin_id = AllowedAdmins::where('email', $data['email'])->get();
            return User::create([
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'name'=>$admin_id[0]['admin_id']
            ]);
        }
        return redirect()->back();
    }
}