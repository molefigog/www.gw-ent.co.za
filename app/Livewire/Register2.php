<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\FileUpload;
use Livewire\Component;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\View\View;
use Filament\Facades\Filament;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Blade;

class Register2 extends Component implements HasForms
{
    use InteractsWithForms;

    public User $user;

    public $name = '';
    public $email = '';
    public $password = '';
    public $passwordConfirmation  = '';
    public $mobile_number = '';
    public $avatar = '';

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('Personal Information')
                    ->extraAttributes(['class' => 'bg-slate-950'])
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(50),
                        PhoneInput::make('mobile_number')->unique(User::class)
                            ->ipLookup(function () {
                                return rescue(fn () => Http::get('https://ipinfo.io/json')->json('country'), app()->getLocale(), report: false);
                            }),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(50)
                            ->unique(User::class)->columnSpanFull(),
                    ])
                    ->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan([
                        'sm' => 2,
                    ]),
                Wizard\Step::make('final step')
                    ->schema([
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required()
                            ->revealable()
                            ->maxLength(50)
                            ->minLength(8)
                            ->same('passwordConfirmation')
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                        TextInput::make('passwordConfirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->required()
                            ->revealable()
                            ->maxLength(50)
                            ->minLength(8)
                            ->dehydrated(false),

                        FileUpload::make('avatar')->label('Profile pic')->image()->directory('avatars')->columnSpanFull(),
                    ])
            ])
                ->columns([
                    'sm' => 2,
                ])
                ->columnSpan([
                    'sm' => 2,
                ])

                ->submitAction(new HtmlString('<button class="bg-cyan-500 size="sm" hover:bg-cyan-600" type="submit">Register</button>'))
             
        ];
    }

    public function register()
    {
        $user = User::create($this->form->getState());
        Filament::auth()->login(user: $user, remember: true);
        session()->flash('success', 'Succefully Registered');
        $this->dispatch('register');
        return $this->redirectIntended('/');
    }


    public function render(): View
    {
        return view('livewire.register2');
    }
}
