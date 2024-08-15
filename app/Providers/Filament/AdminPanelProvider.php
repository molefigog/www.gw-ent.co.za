<?php

namespace App\Providers\Filament;

use Illuminate\Support\Facades\View;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use App\Models\Setting;
use Filament\Navigation\MenuItem;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Auth\Register;
use Filament\Enums\ThemeMode;
use App\Filament\Pages\FilamentInfoWidget;
use App\Filament\Widgets\AccountWidget;
class AdminPanelProvider extends PanelProvider
{

    public function panel(Panel $panel): Panel
    {

        $setting = Setting::first();
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration(Register::class)
            ->passwordReset()
            ->emailVerification()
            ->profile(EditProfile::class)
            ->defaultThemeMode(ThemeMode::Dark)
            ->colors([
                'primary' => 'rgb(106, 76, 196)',
            ])
            ->userMenuItems([
            MenuItem::make()
                ->label('Sale')
                ->url('/top-up')
                ->icon('heroicon-o-user'),
            ])

            ->brandLogo( $setting->logo_url)
            ->favicon( $setting->favicon_url )
            // ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                FilamentInfoWidget::class,
                // AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
            //   ->plugin(FilamentSpatieRolesPermissionsPlugin::make());
    }
public function boot()
{
    // Register the render hook here
    FilamentView::registerRenderHook(
        PanelsRenderHook::PAGE_END,
        function () {
            return view('account');
        }
    );

   // Adjust the logic to get your desired model instance


}
}
