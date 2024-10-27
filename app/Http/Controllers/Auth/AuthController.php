<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Support\Facades\Http;
use Exception;
use App\Helpers\JWTHelper;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller{


	/**
     * Get user login data
     * @return array
     */
	private function getUserLoginData($user = null){
		if(!$user){
			$user = Auth::user();
		}
		$accessToken = $user->createToken('authToken')->accessToken;
        return ['token' => $accessToken];
	}


	/**
     * Authenticate and login user
     * @return \Illuminate\Http\Response
     */
	function login(Request $request){
		$username = $request->username;
		$password = $request->password;
		if(filter_var($username, FILTER_VALIDATE_EMAIL)) {
			Auth::attempt(['email' => $username, 'password' => $password]); //login with email
		}
		else {
			Auth::attempt(['name' => $username, 'password' => $password]); //login with username
		}
        if (!Auth::check()) {
            return $this->reject("Username or password not correct", 400);
        }
		$user = Auth::user();
		//check if user has verified email
		if (!$user->hasVerifiedEmail()) {
			$token = $this->generateUserToken($user);
			return $this->respond(["nextpage" => "/index/verifyemail?token={$token}"]);
		}
		try{
			$otpCode = $this->sendOTPEmail($user); // send otp message and return otp code
			$user->saveOtpCode($otpCode); //save user otp
			$duration = config("auth.otp_duration");
			$token = $this->generateUserToken($user);
			return $this->respond([
				"nextpage" => "/index/verifyotp?token=$token&duration=$duration"
			]);
		}
		catch(Exception $e){
			Auth::logout(); //logout user if already logged in
			throw $e;
		}
	}


	/**
     * Save new user record
     * @return \Illuminate\Http\Response
     */
	function register(UserRegisterRequest $request){
		$modeldata = $request->validated();

		if( array_key_exists("avatar", $modeldata) ){
			//move uploaded file from temp directory to destination directory
			$fileInfo = $this->moveUploadedFiles($modeldata['avatar'], "avatar");
			$modeldata['avatar'] = $fileInfo['filepath'];
		}
		$modeldata['password'] = bcrypt($modeldata['password']);

		//save Users record
		$user = $record = User::create($modeldata);
		$rec_id = $record->id;
		$user->sendEmailVerificationNotification();
		$token = $this->generateUserToken($user);
		return $this->respond(["nextpage" => "/index/verifyemail?token=$token"]);
	}
    /**
    * Send otp message to user phone number via sms
    * must return otp code after message is sent
    * @param {user} current user object
    */
    private function sendOTPEmail($user) {
        $otp       = mt_rand(100000, 999999); // generate a 6-digit random number
        $subject   = "Verify OTP";
        $message   = "Your OTP code is: $otp";
        $recipient = $user->email;
        $this->sendOTPVerificationMail($recipient, $subject, $message);
        return $otp;
    }


	/**
	* Route to login user using credential
	* @route {POST} /auth/validateotp
	* @param {string} path - Express path
	* @param {callback} middleware - Express middleware.
	*/
	function validateotp(Request $request){
		$otpCode = $request->input('otp_code');
		$token = $request->input("token");
		$userId = $this->getUserIDFromJwt($token);
		$user = User::where("id", $userId)->where("otp_code", $otpCode)->first();
		if (!$user) {
			return $this->reject("Invalid OTP code", 400);
		}
		$otpExpireTime = strtotime($user->otp_date);
		if($otpExpireTime < time()) {
			return $this->reject("OTP has expired", 400);
		}
		$user->resetOtpCode(); //reset user otp code
		$loginData = $this->getUserLoginData($user);
		return $this->respond($loginData);
	}
	/**
	* Resend otp message to user
	* @route {POST} /auth/validateotp
	* @param {string} path - Express path
	* @param {callback} middleware - Express middleware.
	*/
	function resendotp(Request $request){
		$token = $request->input("token");
		$userId = $this->getUserIDFromJwt($token);
		$user = User::where("id", $userId)->first();
		if (!$user) {
			return $this->reject("Invalid OTP code", 400);
		}
		$otpCode = $this->sendOTPEmail($user); // send otp message and return otp code
		if(!$otpCode){
			return $this->reject("Unable to send OTP");
		}
		$user->saveOtpCode($otpCode); //save user otp
		return $this->respond("OTP resent successful");
	}


	/**
     * verify user email
     * @return \Illuminate\Http\Response
     */
	public function verifyemail(Request $request) {
		if (!$request->hasValidSignature()) {
			return $this->reject("Invalid/Expired url provided.", 400);
		}
		$token = $request->input("token");
		$userId = $this->getUserIDFromJwt($token);
		$user = User::findOrFail($userId);
		if (!$user->hasVerifiedEmail()) {
			$user->markEmailAsVerified();
		}
		$emailVerifiedPage = config("app.frontend_url") . "/#/index/emailverified";
		return redirect()->to($emailVerifiedPage);
	}


	/**
     * resend email verification link to user email
     * @return \Illuminate\Http\Response
     */
	public function resendverifyemail(Request $request) {
		$token = $request->get("token");
		$userId = $this->getUserIDFromJwt($token);
		$user = User::findOrFail($userId);
		if ($user->hasVerifiedEmail()) {
			return $this->reject("Email already verified.", 400);
		}
		$user->sendEmailVerificationNotification();
		return $this->respond("Email verification link has been resent");
	}


	/**
     * send password reset link to user email
     * @return \Illuminate\Http\Response
     */
	public function forgotpassword(Request $request) {
		$modeldata = $request->all();
		$validator = Validator::make($modeldata,
		[
			'email' => "required|email",
		]);
		if ($validator->fails()) {
			return $this->reject($validator->errors(), 400);
		}
		try{
			$response = Password::sendResetLink($modeldata);
			switch ($response) {
				case Password::RESET_LINK_SENT:
					return $this->respond(trans($response));
				case Password::INVALID_USER:
					return $this->reject(trans($response), 404);
			}
			return $this->reject($response, 500);
		}
		catch (Exception $ex) {
			return $this->reject($ex->getMessage());
		}
	}


	/**
     * Reset user password
     * @return \Illuminate\Http\Response
     */
	public function resetpassword(Request $request) {
		$modeldata = $request->all();
		$validator = Validator::make($modeldata,
		[
			'email' => 'required|email',
			'token' => 'required|string',
			"password" => "required|same:confirm_password",
		]);
		if ($validator->fails()) {
			return $this->reject($validator->errors(), 400);
		}
		$credentials = $validator->valid();
		$reset_password_status = Password::reset($credentials, function ($user, $password) {
			$user->password = bcrypt($password);
			$user->save();
		});
		if ($reset_password_status == Password::INVALID_TOKEN) {
			return $this->reject("Invalid token", 400);
		}
		return $this->respond("Password changed");
	}


	/**
     * Get the response for a successful password reset.
     *
     * @param  string  $response
     * @return \Illuminate\Http\Response
     */
	protected function sendResetResponse(Request $request, $response)
	{
		return $this->respond("Password reset link sent to user email");
	}


    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
       return $this->reject(trans($response), 500);
	}


	/**
     * generate token with user id
     * @return string
     */
	private function generateUserToken($user = null){
		return JWTHelper::encode($user->id);
	}


	/**
     * validate token and get user id
     * @return string
     */
	private function getUserIDFromJwt($token){
		$userId =  JWTHelper::decode($token);
 		return $userId;
	}
}
