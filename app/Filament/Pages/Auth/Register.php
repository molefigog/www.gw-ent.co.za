<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\HtmlString;
use App\Models\User;
use Illuminate\Http\Request;

class Register extends BaseRegister
{
    protected ?string $maxWidth = '2xl';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Contact')
                        ->schema([
                            $this->getNameFormComponent(),
                            PhoneInput::make('mobile_number')->unique(User::class)
                            ->ipLookup(function () {
                                return rescue(fn () => Http::get('https://ipinfo.io/json')->json('country'), app()->getLocale(), report: false);
                            }),
                            $this->getEmailFormComponent()->columnSpanFull(),
                        ]),
                    Wizard\Step::make('Avatar')
                        ->schema([
                            FileUpload::make('avatar')->label('Profile pic')->image()->directory('avatars')->columnSpanFull(),

                        ]),
                    Wizard\Step::make('Password')
                        ->schema([
                            $this->getPasswordFormComponent(),
                            $this->getPasswordConfirmationFormComponent(),
                        ]),

                ])->submitAction(new HtmlString(Blade::render(<<<BLADE
                    <x-filament::button
                        type="submit"
                        size="sm"
                        wire:submit="register"
                    >
                        Register
                    </x-filament::button>
                    BLADE))),
            ]);
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function registered(Request $request, $user)
    {
        return redirect('/');
    }
}
