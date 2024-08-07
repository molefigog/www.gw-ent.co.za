<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    /**
     * Handle a request to resend a verification email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendVerificationEmail(Request $request)
    {
        $user = Auth::user();

        if ($user && !$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();

            return response()->json(['message' => 'Verification link sent!'], 200);
        }

        return response()->json(['message' => 'Email already verified or user not authenticated'], 400);
    }
}
