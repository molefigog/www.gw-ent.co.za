<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GenreResource\Pages;
use App\Filament\Resources\GenreResource\RelationManagers;
use App\Models\Genre;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GenreResource extends Resource
{
    protected static ?string $model = Genre::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-minus';

    protected static ?string $navigationGroup = 'Products';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([

                TextInput::make('title'),

                FileUpload::make('image')->preserveFilenames()->maxSize(512)->disk('public')
    ->directory('images'),

             ])->Columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                  ImageColumn::make('image')->size(30),
                TextColumn::make('title'),
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
            'index' => Pages\ListGenres::route('/'),
            'create' => Pages\CreateGenre::route('/create'),
            'edit' => Pages\EditGenre::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}
}
