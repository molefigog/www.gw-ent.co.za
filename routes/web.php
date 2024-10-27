<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\BeatController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Livewire\Payment;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\MigrationController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\PurchasedMusicController;
use Carbon\Carbon;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\BookingController;
use App\Livewire\Mmino;
use App\Livewire\MusicSection;
use App\Http\Controllers\SocialShareController;
use App\Http\Controllers\MpesaController;
use App\Http\Controllers\SpotifyController;

Route::get('/cancel', function () {
    return view('paypal.cancel');
});
Route::get('/signup', function () {
    return view('auth.signup');
});
Route::get('/mpesa-payment', function () {
    return view('tzn');
});

Route::get('/artist/{artist}', function ($artistName) {
    return view('songs_by_artist', ['artistName' => $artistName]);
});

Route::get('/songs/{artist}', function ($artistName) {
    return view('songs_by_artist', ['artistName' => $artistName]);
});
Route::get('/', Mmino::class)->name('gee');
Route::get('beats', MusicSection::class)->name('beats');
// Route::get('/msingle/{slug}', ShowMusic::class)->name('msingle.slug');

Route::get('/mpesa/success', [MpesaController::class, 'showSuccessPage'])->name('mpesa.success');

Route::get('mpesa/error', [MpesaController::class, 'showErrorPage'])->name('mpesa.error');
Route::get('/success2', [PaypalController::class, 'returnUrl'])->name('success2');

Route::get('/success', [PaypalController::class, 'handleSuccess'])->name('success');
Route::post('/ipn', [PaypalController::class, 'handleIPN']);

Route::get('/ajax-paginate',[SiteController::class,'ajax_paginate'])->name('ajax.paginate');

Route::get('/live-search', [SiteController::class, 'liveSearch'])->name('liveSearch');
// Route::get('/', [SiteController::class, 'landingPage'])->name('gee');
Route::get('beatz', [SiteController::class, 'beatsPage'])->name('beatz');
Route::get('songs', [SiteController::class, 'songsPage'])->name('songs');
// Route::get('songs', [SiteController::class, 'songsPage'])->name('songs');
Route::get('/sitemap', [SiteController::class, 'sitemap'])->name('sitemap');
Route::post('otpdownloads', [SiteController::class, 'otpDownloads'])->name('otpdownloads');
Route::get('getdownloads', [SiteController::class, 'getDownloads'])->name('getdownloads');
Route::post('dl', [SiteController::class, 'getDl'])->name('getDl');

Route::get('msingle/{slug}', [MusicController::class, 'showBySlug'])
    ->where('slug', '[a-zA-Z0-9\-]+')  // Regular expression pattern for the slug
    ->name('msingle.slug');

Route::get('beat/{slug}', [BeatController::class, 'showBySlug'])
->where('slug', '[a-zA-Z0-9\-]+')  // Regular expression pattern for the slug
->name('beat.slug');
// Route::get('songs', [MusicController::class, 'genre'])->name('genres');

// installation routes

Route::get('/setup-database', [MigrationController::class, 'showDatabaseSetupForm'])->name('setup-database');
Route::post('/run-migrations', [MigrationController::class, 'runMigrations'])->name('run-migrations');
Route::post('/run-seeder', [MigrationController::class, 'runSeeder'])->name('run-seeder');
Route::get('/run-optimize', [MigrationController::class, 'runOptimize'])->name('run.optimize');

Route::get('social-share', [SocialShareController::class, 'index']);
// sms routes

Route::get('/data', [DataController::class, 'index']);
Route::get('/send-sms', [SMSController::class, 'sendSMSForm'])->name('send-sms-form');
Route::get('/send-sms2', [SMSController::class, 'sendSMSForm2'])->name('send-sms-form2');
Route::post('/send-sms', [SMSController::class, 'sendSMS'])->name('send-sms-send');
Route::post('/send-sms1', [SMSController::class, 'sendSMS2'])->name('send-sms-send2');
// Route::post('/v1/mpesa', [WebhookController::class, 'store']);

Route::get('music/download/{mp3}', [MusicController::class, 'downloadMp3'])->name('mp3.download');
Route::get('music/download/{music}', [MpesaController::class, 'downloadSong'])->name('music.download');
Route::get('download/{beat}', [MpesaController::class, 'downloadBeat'])->name('beat.download');


Route::get('/bookings/artist/{artistName}', [BookingController::class, 'showArtistBookings'])->name('bookings.artist');


Route::post('/manual', [TopUpController::class, 'processOrder'])->name('manual');
Route::post('/order', [TopUpController::class, 'beatOrder'])->name('beat-order');
Route::post('/check-otp', [TopUpController::class, 'checkOtp'])->name('check-otp');
// music routes
Route::get('/download-music/{music_id}', [MusicController::class, 'downloadMusic'])
    ->middleware('canDownloadMusic')
    ->name('download-music');

Route::get('/download-beat/{beat_id}', [BeatController::class, 'downloadBeat'])
    ->middleware('canDownloadBeat')
    ->name('download-beat');

Route::post('/buy-beat', [BeatController::class, 'buyBeat'])->name('buy-beat');
Route::post('/buy-music', [MusicController::class, 'buyMusic'])->name('buy-music');
Route::post('/pay', [MusicController::class, 'pay'])->name('pay');
Route::get('about', [ProductController::class, 'about'])->name('about');
Route::get('/songs/genre/{genre}', [MusicController::class, 'songsByGenre'])->name('songs-by-genre');

Route::get('/app-reset', [OwnerController::class, 'runScheduledTasks']);
Route::get('/expired-items', [OwnerController::class, 'wipeOut']);
Route::get('/generate-sitemap', [OwnerController::class, 'siteMap']);
// Route::get('/email-monthly-schedule', [OwnerController::class, 'monthlyEmail']);

Route::post('/clear-download-link', [MusicController::class, 'clearDownloadLink'])->name('clear-download-link');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name(
        'profile.edit'
    );
    Route::patch('/profile', [ProfileController::class, 'update'])->name(
        'profile.update'
    );
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name(
        'profile.destroy'
     );
    Route::get('verify-email', EmailVerificationPromptController::class);

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');
                Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('pass.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('pass.update');
});

Route::get('/payment/{music}', Payment::class)->name('payment');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('products', ProductController::class);
    Route::get('/purchased-musics', [MusicController::class, 'purchasedMusics'])->name('purchased-musics');
    Route::get('/purchased-beatz', [BeatController::class, 'purchasedBeats'])->name('purchased-beatz');
    Route::get('/transaction', [PaypalController::class, 'index'])->name('log');
    Route::get('/sales', [PurchasedMusicController::class, 'index'])->name('sales');

    Route::post('/temporary-upload', [ProfileController::class,'temporaryUpload'])->name('avatar.temp.upload');
    Route::patch('/update-avatar', [ProfileController::class,'updateAvatar'])->name('avatar.update');
    Route::post('/revert-upload', [ProfileController::class, 'revertUpload'])->name('avatar.revert.upload');

});

Route::get('/google/redirect', [App\Http\Controllers\GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/google/callback', [App\Http\Controllers\GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');

Route::prefix('facebook')->name('facebook.')->group( function(){
    Route::get('auth', [App\Http\Controllers\FaceBookController::class, 'loginUsingFacebook'])->name('login');
    Route::get('callback', [App\Http\Controllers\FaceBookController::class, 'callbackFromFacebook'])->name('callback');
});
// routes/web.php
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
});
// routes/web.php
Route::get('/generate-pdf/{artistId}', [BookingController::class, 'generatePdf'])->name('generate.pdf');
// routes/web.php


Route::get('spotify/redirect', [SpotifyController::class, 'redirectToSpotify'])->name('spotify.redirect');
Route::get('callback', [SpotifyController::class, 'handleCallback'])->name('spotify.callback');
Route::get('songs', [SpotifyController::class, 'getSongs'])->name('songs.index');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::post('/mails', [App\Http\Controllers\InboundEmailController::class, 'handle']);
