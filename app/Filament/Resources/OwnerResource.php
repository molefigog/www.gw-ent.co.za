<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OwnerResource\Pages;
use App\Filament\Resources\OwnerResource\RelationManagers;
use App\Models\Owner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\Card;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OwnerResource extends Resource
{
    protected static ?string $model = Owner::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';
     protected static ?string $modelLabel = 'Admin Contact';
    protected static ?string $navigationGroup = 'General Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Card::make()
                ->schema([
                TextInput::make('email')->email() ,
                TextInput::make('whatsapp')->tel()->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                TextInput::make('facebook')->url(),
                TextInput::make('address'),

            ])->Columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 TextColumn::make('email'),
                  TextColumn::make('whatsapp'),
                   TextColumn::make('facebook'),
                    TextColumn::make('address'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(auth()->user()->role == 1 ? true : false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible(auth()->user()->role == 1 ? true : false),
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
            'index' => Pages\ListOwners::route('/'),
            'create' => Pages\CreateOwner::route('/create'),
            'edit' => Pages\EditOwner::route('/{record}/edit'),
        ];
    }
}
