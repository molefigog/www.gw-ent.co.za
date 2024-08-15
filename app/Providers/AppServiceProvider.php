<?php

namespace App\Providers;

use Illuminate\Support\Facades;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewInstance;
use App\Models\MailSetting;
use App\Models\Genre;
use App\Models\Owner;
use App\Models\Setting;
use App\Models\User;
use App\Http\Responses\LogoutResponse;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
// use App\Http\Responses\RegistrationResponse;
// use Filament\Http\Responses\Auth\Contracts\RegistrationResponse as RegistrationResponseContract;
use App\Http\Responses\LoginResponse;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
        // $this->app->bind(RegistrationResponseContract::class, RegistrationResponse::class);
        $this->app->bind(LoginResponseContract::class, LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function (ViewInstance $view) {
            $setting = Setting::orderBy('created_at', 'desc')
                ->select('site', 'image', 'logo', 'favicon', 'description')
                ->first();
            $admin = Owner::orderBy('created_at', 'desc')
                ->select('email', 'whatsapp', 'facebook', 'address')
                ->first();
            $artists = User::orderBy('name')->get();

            $genres = Genre::orderBy('created_at', 'desc')->pluck('title');
            $view->with('setting', $setting);
            $view->with('admin', $admin);
            $view->with('artists', $artists);
            $view->with('genres', $genres);
        });
    }
}
