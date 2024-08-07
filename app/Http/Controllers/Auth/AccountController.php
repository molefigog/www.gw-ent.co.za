<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UsersAccountEditRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Exception;
/**
 * Account Page Controller
 * @category  Controller
 */
class AccountController extends Controller{


	/**
     * Select user account data
     * @return \Illuminate\View\View
     */
	function index(){
		$rec_id = Auth::id();
		$query = User::query();
		$record = $query->findOrFail($rec_id,  User::accountviewFields());
		return $this->respond($record);
	}


	/**
     * Update user account data
     * @return \Illuminate\View\View;
     */
	function edit(UsersAccountEditRequest $request){
		$rec_id = Auth::id();
		$query = User::query();
		$record = $query->findOrFail($rec_id, User::accounteditFields());
		if ($request->isMethod('post')) {
			$modeldata = $request->validated();

		if( array_key_exists("avatar", $modeldata) ){
			//move uploaded file from temp directory to destination directory
			$fileInfo = $this->moveUploadedFiles($modeldata['avatar'], "avatar");
			$modeldata['avatar'] = $fileInfo['filepath'];
		}
			$record->update($modeldata);
			$this->afterAccountedit($rec_id, $record);
		}
		return $this->respond($record);
	}
    /**
     * After page record updated
     * @param string $rec_id // updated record id
     * @param array $record // updated page record
     */
    private function afterAccountedit($rec_id, $record){
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
	function currentuserdata(){
		$user = auth()->user();
		return $this->respond($user);
	}


	/**
     * Change user account password
     * @return \Illuminate\Http\Response
     */
	public function changepassword(Request $request)
	{
		$request->validate([
			'oldpassword' => ['required'],
			'newpassword' => ['required'],
			'confirmpassword' => ['same:newpassword'],
		]);
		$userid = auth()->id();
		$user = User::find($userid);
		$oldPasswordText = $request->oldpassword;
		$oldPasswordHash = $user->password;
		if(!Hash::check($oldPasswordText, $oldPasswordHash)){
			return $this->reject("Current password is incorrect");
		}
		$modeldata = ['password' => Hash::make($request->newpassword)];
		$user->update($modeldata);
		return $this->respond("Password changed");
	}
}
