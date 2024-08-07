<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'Users';
    protected static ?string $navigationGroup = 'Manage User';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')->live(),
                        TextInput::make('email')->live()->email(),
                        TextInput::make('mobile_number')->live()->tel(),
                        TextInput::make('password')->live()->password()->readOnlyOn('edit'),
                        FileUpload::make('avatar')->image()->avatar()->imageEditor()->circleCropper()
                    ])->Columns(2)->visible(auth()->user()->role == 1 ? true : false),

            ]);
    }

    public static function table(Table $table): Table
    {
        $bulkActions = [
            Tables\Actions\DeleteBulkAction::make(),
        ];

        // Check if the authenticated user's role is 1 (admin)
        if (auth()->user()->role == 1) {
            // Add bulk actions for admin users
            $table->bulkActions($bulkActions);
        }

        return $table
            ->columns([
                ImageColumn::make('avatar')->size(30),
                TextColumn::make('id'),
                TextColumn::make('name') ->searchable(),
                TextColumn::make('email') ->searchable()->visible(auth()->user()->role == 1 ? true : false),
                TextInputColumn::make('balance')->visible(auth()->user()->role == 1 ? true : false),

                TextColumn::make('mobile_number')->visible(auth()->user()->role == 1 ? true : false),
            ])
            ->filters([
                Filter::make('balance')
                    ->label('Credits')
                    ->query(function ($query) {
                        return $query->where('balance', '>', 0);
                    })
            ])

            ->actions([
                Tables\Actions\EditAction::make()->visible(auth()->user()->role == 1 ? true : false),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // public static function getGloballySearchableAttributes(): array
    // {
    //     return ['name', 'email'];
    // }

    // public static function getGlobalSearchResultDetails(Model $record): array
    // {
    //     return [
    //         "name" => $record->name,
    //         "email" => $record->email,
    //     ];
    // }
}
