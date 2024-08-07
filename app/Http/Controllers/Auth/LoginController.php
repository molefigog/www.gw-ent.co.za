<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    use ThrottlesLogins;
    protected $maxAttempts = 1;
    protected $decayMinutes = 1;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    //  protected function setIntendedUrl(Request $request, $slug)
    //  {
    //      $baseUrl = 'https://www.gw-ent.co.za';
    //      $intendedUrl = "{$baseUrl}/{$slug}";
    //      $request->session()->put('url.intended', $intendedUrl);
    //      Log::info('Intended URL set: ' . $intendedUrl);
    //  }

     

    protected function authenticated(Request $request, $user)
    {
        // Check if there's an intended URL in the session
        $intendedUrl = session('intended_url');

        if ($intendedUrl) {
            // Clear the intended URL from the session
            session()->forget('intended_url');
            return redirect($intendedUrl);
        }

        // If no intended URL, redirect to the default page
        return redirect('/');
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Create a new controller instance.
     *
     * @return RedirectResponse
     *
     */

    public function login(Request $request)
    {
        Log::info('Login method called');
        $this->validate($request, [
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = $this->determineLoginType($request->input('login'));

        $credentials = [];

        if ($loginType === 'mobile_number') {
            $mobileNumber = strlen($request->input('login')) === 11 ? $request->input('login') : '266' . $request->input('login');
            $credentials = [
                'mobile_number' => $mobileNumber,
                'password' => $request->input('password'),
            ];
        } else {
            $credentials = [
                'email' => $request->input('login'),
                'password' => $request->input('password'),
            ];
        }

        if (Auth::attempt($credentials)) {
            // Check if there's an intended URL in the session
            $intendedUrl = session('intended_url');

            if ($intendedUrl) {
                // Clear the intended URL from the session
                session()->forget('intended_url');
                return redirect($intendedUrl);
            }

            return redirect()->intended(url('/'));
        }

        return back()->withErrors([$loginType => 'Invalid credentials']);
    }

    protected function sendFailedLoginResponse(Request $request, $loginType)
    {
        throw ValidationException::withMessages([
            $loginType => [trans('auth.failed')],
        ]);
    }

    protected function determineLoginType($login)
    {
        return filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile_number';
    }


    public function loginWithMobile(Request $request)
    {
        $this->validate($request, [
            'mobile_number' => 'required|digits:8',
            'password' => 'required|string',
        ]);

        $mobileNumber = '266' . $request->input('mobile_number'); // Adding the prefix '266'

        $credentials = [
            'mobile_number' => $mobileNumber,
            'password' => $request->input('password'),
        ];

        // Store the intended URL in the session
        $intendedUrl = $request->session()->get('url.intended', url('/'));

        if (Auth::attempt($credentials)) {
            return redirect()->intended($intendedUrl);
        }

        return back()->withErrors(['mobile_number' => 'Invalid credentials']);
    }
}
