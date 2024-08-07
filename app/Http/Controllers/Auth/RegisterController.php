<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = "/";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile_number' => 'phone:INTERNATIONAL,US,BE,LS,BW,ZA,MZ,ZW',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'phone.phone' => 'The phone number must be a valid phone number.',
        ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        //  dd($data);
        $verificationToken = sha1(time() . $data['email']);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile_number'=> $data['mobile_number'],
            'password' => Hash::make($data['password']),
            'balance' => 0, // Default balance
            'avatar' => 'default_avatar.png', // Default avatar
            'email_verification_token' => $verificationToken,
        ]);

        $user->sendEmailVerificationNotification();

        return $user;
    }

    protected function registered(Request $request, $user)
    {
        return redirect()->route('gee')->withSuccess(__('Account registered successfully! Please check your email for verification.'));
    }

    public function registerWithMobile(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        Auth::login($user);

        return $this->registered($request, $user) ?: redirect($this->redirectPath());
    }
}
