<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingsResource\Pages;
use App\Filament\Resources\BookingsResource\RelationManagers;
use App\Models\Bookings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Models\User;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Filament\Support\RawJs;
use Filament\Forms\Components\TimePicker;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Illuminate\Support\Facades\Http;
class BookingsResource extends Resource
{
    protected static ?string $model = Bookings::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Bookings';
    protected static ?string $navigationGroup = 'Artist';

    public static function form(Form $form): Form
    {
        // $user = User::all();
        return $form
            ->schema([
                Section::make('Artist')
                    ->schema([
                        TextInput::make('user_id')
                            ->readOnly()
                            ->required()
                            ->default(Auth::user()->id)->label(Auth::user()->name)
                            ->helperText(str('Do not edit this..')->inlineMarkdown()->toHtmlString()),
                        TextInput::make('artist')->required()
                            ->default(Auth::user()->name),
                        Repeater::make('bank')
                            ->schema([
                                TextInput::make('name')->required()->label('Bank Name'),
                                TextInput::make('acc_number')->required()->label('Account Number'),
                                TextInput::make('mpesa')->required(),
                            ])
                            ->columnSpan(2),
                    ]),

                Section::make('Pricing')
                    ->schema([
                        Repeater::make('pricing')->label('LS Prices')
                            ->schema([
                                TextInput::make('amount')->required()->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->numeric(),
                                TextInput::make('duration')->required(),
                            ]),
                        Repeater::make('int_pricing')->label('SA Prices')
                            ->schema([
                                TextInput::make('amount')->required()->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->numeric(),
                                TextInput::make('duration')->required(),
                            ]),
                        Repeater::make('transport')
                            ->schema([
                                TextInput::make('local')->required()
                                ->helperText(new HtmlString('Any where around <strong>Lesotho</strong>')),

                                TextInput::make('south_africa')->required()
                                ->helperText(new HtmlString('Applicable to Free State and Gauteng only <strong>South Africa</strong>')),
                            ])
                    ]),
                Section::make('Info')
                    ->schema([
                        Repeater::make('contact')
                            ->schema([
                                PhoneInput::make('tell')
                                ->ipLookup(function () {
                                    return rescue(fn () => Http::get('https://ipinfo.io/json')->json('country'), app()->getLocale(), report: false);
                                }),
                                PhoneInput::make('tell2')
                                ->ipLookup(function () {
                                    return rescue(fn () => Http::get('https://ipinfo.io/json')->json('country'), app()->getLocale(), report: false);
                                }),
                                TextInput::make('email')->required(),

                            ]),
                        FileUpload::make('image')->directory('images')->disk('public'),
                    ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->size(30),
                TextColumn::make('artist'),
                TextColumn::make('user.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBookings::route('/create'),
            'edit' => Pages\EditBookings::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::user()->id);
    }
}
