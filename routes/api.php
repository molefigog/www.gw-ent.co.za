<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AndroidApiController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\MpesaController;
use App\Http\Controllers\CpayController;
use App\Http\Controllers\Api\BeatsController;
use App\Http\Controllers\Api\GenresController;
use App\Http\Controllers\Api\MailSettingsController;
use App\Http\Controllers\Api\MusicController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AccountController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\Components_dataController;
use App\Http\Controllers\Api\FileUploaderController;
use Openpesa\Pesa\Facades\Pesa;
use App\Http\Controllers\TzController;
use Illuminate\Support\Facades\Log;
// Route::get('/charge', [TzController::class, 'charge']);
Route::get('/generate-session-key', [TzController::class, 'generateSessionKey']);

Route::get('/mpesa/c2b-payment', [TzController::class, 'initiateC2BPayment']);

Route::get('/charge', function (Request $request) {
    Log::info("Charge route accessed.");
    $options = [
        'api_key' => "FzEf1L4Ee4RkRCYVQE1cjfhKAWN2OMbl",
        'public_key' => "MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEArv9yxA69XQKBo24BaF/D+fvlqmGdYjqLQ5WtNBb5tquqGvAvG3WMFETVUSow/LizQalxj2ElMVrUmzu5mGGkxK08bWEXF7a1DEvtVJs6nppIlFJc2SnrU14AOrIrB28ogm58JjAl5BOQawOXD5dfSk7MaAA82pVHoIqEu0FxA8BOKU+RGTihRU+ptw1j4bsAJYiPbSX6i71gfPvwHPYamM0bfI4CmlsUUR3KvCG24rB6FNPcRBhM3jDuv8ae2kC33w9hEq8qNB55uw51vK7hyXoAa+U7IqP1y6nBdlN25gkxEA8yrsl1678cspeXr+3ciRyqoRgj9RD/ONbJhhxFvt1cLBh+qwK2eqISfBb06eRnNeC71oBokDm3zyCnkOtMDGl7IvnMfZfEPFCfg5QgJVk1msPpRvQxmEsrX9MQRyFVzgy2CWNIb7c+jPapyrNwoUbANlN8adU1m6yOuoX7F49x+OjiG2se0EJ6nafeKUXw/+hiJZvELUYgzKUtMAZVTNZfT8jjb58j8GVtuS+6TM2AutbejaCV84ZK58E2CRJqhmjQibEUO6KPdD7oTlEkFy52Y1uOOBXgYpqMzufNPmfdqqqSM4dU70PO8ogyKGiLAIxCetMjjm6FCMEA3Kc8K0Ig7/XtFm9By6VxTJK1Mg36TlHaZKP6VzVLXMtesJECAwEAAQ==",
        'service_provider_code' => '000000',
        'country' => 'LES',
        'currency' => 'LSL',
        'persistent_session' => false,
        'env' => 'sandbox'
    ];
    Log::info("Options for TzController: ", $options);

    $number = $request->input('input_CustomerMSISDN');
    $mssid = '266' . $number;

    $tzController = new TzController($options);
    $response = $tzController->c2b([
       'input_Amount' => $request->input('input_Amount'),
        'input_Country' => 'LES',
        'input_Currency' => 'LSL',
        'input_CustomerMSISDN' => $mssid,
        'input_ServiceProviderCode' => '000000',
        'input_ThirdPartyConversationID' => 'GW' . rand(1000, 9999),
        'input_TransactionReference' => 'asdodfdferre',
        'input_PurchasedItemsDesc' => $request->input('input_PurchasedItemsDesc')
    ]);

    Log::info("Charge response: " . print_r($response, true));

    // return response()->json($response);
        $responseCode = $response['output_ResponseCode'];
        $responseDesc = $response['output_ResponseDesc'];
        return response()->json([
            'code' => $responseCode,
            'description' => $responseDesc
        ]);
});

Route::post('/patala', [CpayController::class, 'makePayment'])->name('patala');
Route::post('/otp', [CpayController::class, 'confirmPayment'])->name('otp');

Route::post('/beatpay', [MpesaController::class, 'beatPay'])->name('beat.pay');
Route::post('/uploadpay', [MpesaController::class, 'uploadStatus'])->name('upload.status');
Route::post('/pay', [MpesaController::class, 'pay'])->name('m-pesa');
Route::get('/confirmpay', [MpesaController::class, 'confirmPayment'])->name('confirmpayment');
// Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::get('/email-monthly-schedule', [OwnerController::class, 'monthlyEmail']);

Route::post('/mpesu', [MpesaController::class, 'mpesaTransaction'])->name('mpesu');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::post('/mpesa/payment', [AndroidApiController::class, 'makePayment']);

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    })
    ->name('api.user');

// Route::post('/v1/mpesa', [WebhookController::class, 'store']);
Route::post('/v1/{useremail}', [AndroidApiController::class, 'store']);

Route::post('/v1/{useremail}', [AndroidApiController::class, 'store']);

Route::post('/v2/mpesa', [WebhookController::class, 'manualPay']);
Route::get('/music', [MusicController::class, 'index']);

Route::middleware(['auth:api'])->group(function () {

    /* routes for Beats Controller  */
        Route::get('beats/', [BeatsController::class, 'index']);
        Route::get('beats/index', [BeatsController::class, 'index']);
        Route::get('beats/index/{filter?}/{filtervalue?}', [BeatsController::class, 'index']);
        Route::get('beats/view/{rec_id}', [BeatsController::class, 'view']);
        Route::post('beats/add', [BeatsController::class, 'add']);
        Route::any('beats/edit/{rec_id}', [BeatsController::class, 'edit']);
        Route::any('beats/delete/{rec_id}', [BeatsController::class, 'delete']);

        Route::get('genres/', [GenresController::class, 'index']);
        Route::get('genres/index', [GenresController::class, 'index']);
        Route::get('genres/index/{filter?}/{filtervalue?}', [GenresController::class, 'index']);
        Route::get('genres/view/{rec_id}', [GenresController::class, 'view']);
        Route::post('genres/add', [GenresController::class, 'add']);
        Route::any('genres/edit/{rec_id}', [GenresController::class, 'edit']);
        Route::any('genres/delete/{rec_id}', [GenresController::class, 'delete']);

    /* routes for MailSettings Controller  */
        Route::get('mailsettings/', [MailSettingsController::class, 'index']);
        Route::get('mailsettings/index', [MailSettingsController::class, 'index']);
        Route::get('mailsettings/index/{filter?}/{filtervalue?}', [MailSettingsController::class, 'index']);
        Route::get('mailsettings/view/{rec_id}', [MailSettingsController::class, 'view']);
        Route::post('mailsettings/add', [MailSettingsController::class, 'add']);
        Route::any('mailsettings/edit/{rec_id}', [MailSettingsController::class, 'edit']);
        Route::any('mailsettings/delete/{rec_id}', [MailSettingsController::class, 'delete']);

    /* routes for Music Controller  */
        Route::get('music/', [MusicController::class, 'index']);
        Route::get('music/index', [MusicController::class, 'index']);
        Route::get('music/index/{filter?}/{filtervalue?}', [MusicController::class, 'index']);
        Route::get('music/view/{rec_id}', [MusicController::class, 'view']);
        Route::post('music/add', [MusicController::class, 'add']);
        Route::any('music/edit/{rec_id}', [MusicController::class, 'edit']);
        Route::any('music/delete/{rec_id}', [MusicController::class, 'delete']);

        Route::get('settings/', [SettingsController::class, 'index']);
        Route::get('settings/index', [SettingsController::class, 'index']);
        Route::get('settings/index/{filter?}/{filtervalue?}', [SettingsController::class, 'index']);
        Route::get('settings/view/{rec_id}', [SettingsController::class, 'view']);
        Route::post('settings/add', [SettingsController::class, 'add']);
        Route::any('settings/edit/{rec_id}', [SettingsController::class, 'edit']);
        Route::any('settings/delete/{rec_id}', [SettingsController::class, 'delete']);

    /* routes for Users Controller  */
        Route::get('users/', [UsersController::class, 'index']);
        Route::get('users/index', [UsersController::class, 'index']);
        Route::get('users/index/{filter?}/{filtervalue?}', [UsersController::class, 'index']);
        Route::get('users/view/{rec_id}', [UsersController::class, 'view']);
        Route::any('profile/edit', [AccountController::class, 'edit']);
        Route::get('profile', [AccountController::class, 'index']);
        Route::post('profile/changepassword', [AccountController::class, 'changepassword']);
        Route::get('profile/currentuserdata', [AccountController::class, 'currentuserdata']);
        Route::post('users/add', [UsersController::class, 'add']);
        Route::any('users/edit/{rec_id}', [UsersController::class, 'edit']);
        Route::any('users/delete/{rec_id}', [UsersController::class, 'delete']);

    });

    Route::get('home', [HomeController::class, 'index']);

        Route::post('auth/register', [AuthController::class, 'register']);
        Route::post('auth/login1', [AuthController::class, 'login']);
        Route::get('login', [AuthController::class, 'login'])->name('login1');

        Route::post('auth/forgotpassword', [AuthController::class, 'forgotpassword'])->name('password.reset1');
        Route::post('auth/resetpassword', [AuthController::class, 'resetpassword']);

        Route::get('components_data/genre_id_option_list/{arg1?}', [Components_dataController::class, 'genre_id_option_list']);
        Route::get('components_data/genre_id_option_list_2/{arg1?}', [Components_dataController::class, 'genre_id_option_list_2']);
        Route::get('components_data/users_name_exist/{arg1?}', [Components_dataController::class, 'users_name_exist']);
        Route::get('components_data/users_email_exist/{arg1?}', [Components_dataController::class, 'users_email_exist']);
        Route::get('components_data/users_password_exist/{arg1?}', [Components_dataController::class, 'users_password_exist']);
        Route::get('components_data/users_mobile_number_exist/{arg1?}', [Components_dataController::class, 'users_mobile_number_exist']);
        Route::get('components_data/getcount_music/{arg1?}', [Components_dataController::class, 'getcount_music']);
        Route::get('components_data/getcount_users/{arg1?}', [Components_dataController::class, 'getcount_users']);
        Route::get('components_data/getcount_beats/{arg1?}', [Components_dataController::class, 'getcount_beats']);
        Route::get('components_data/getcount_genres/{arg1?}', [Components_dataController::class, 'getcount_genres']);

    /* routes for FileUpload Controller  */
    Route::post('fileuploader/upload/{fieldname}', [FileUploaderController::class, 'upload']);
    Route::post('fileuploader/s3upload/{fieldname}', [FileUploaderController::class, 's3upload']);
    Route::post('fileuploader/remove_temp_file', [FileUploaderController::class, 'remove_temp_file']);
    Route::post('/get_data', [MusicController::class, 'getMusicData']);
    Route::post('/check-music-file', [MusicController::class, 'checkFile'])->name('check-music-file');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
