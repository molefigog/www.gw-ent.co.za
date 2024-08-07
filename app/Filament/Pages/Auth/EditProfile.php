<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;
use Ysfkaya\FilamentPhoneInput\Infolists\PhoneEntry;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Illuminate\Support\Facades\Http;
class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('avatar')->maxSize(512)->required()
                        ->disk('public')->directory('avatars')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif']),


                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                PhoneInput::make('mobile_number')
    ->ipLookup(function () {
        return rescue(fn () => Http::get('https://ipinfo.io/json')->json('country'), app()->getLocale(), report: false);
    }),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
