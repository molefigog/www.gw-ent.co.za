<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        // Apply the 'guest' middleware to all routes except 'logout'
        $this->middleware('guest')->except('logout');

        // Apply the 'auth' middleware to the 'logout' route
        $this->middleware('auth')->only('logout');
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
