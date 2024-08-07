<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UsersRegisterRequest;
use Exception;
use App\Helpers\JWTHelper;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;
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
			$user = auth()->user();
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
		$user = auth()->user();
		$loginData = $this->getUserLoginData($user);
        return $this->respond($loginData);
	}


	/**
     * Save new user record
     * @return \Illuminate\Http\Response
     */
	function register(UsersRegisterRequest $request){
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
		$this->afterUserregister($record);
		$loginData =  $this->getUserLoginData($user);
		return $this->respond($loginData);
	}
    /**
     * After new record created
     * @param array $record // newly created record
     */
    private function afterUserregister($record){
        $imageFilename = basename($record->avatar);
        $imaglocation = 'avatars/' . $imageFilename;
        try {
            $record->update([
                'avatar' => $imaglocation,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update record: ' . $e->getMessage());
        }
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
