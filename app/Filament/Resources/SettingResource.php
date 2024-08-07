<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';
         protected static ?string $modelLabel = 'Settings';
    protected static ?string $navigationGroup = 'General Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Card::make()
                ->schema([
                TextInput::make('site'),
                TextInput::make('tagline'),
               FileUpload::make('logo')->preserveFilenames()->maxSize(512)->disk('public')->directory('images'),
               FileUpload::make('favicon')->preserveFilenames()->maxSize(512)->disk('public')->directory('images'),
               TinyEditor::make('description')->columnSpanFull(),


                FileUpload::make('image')->preserveFilenames()->maxSize(512)->disk('public')->directory('images')->columnSpanFull(),
            ])->Columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')->size(30),
                 ImageColumn::make('favicon')->size(30),
                  ImageColumn::make('image')->size(30),
                TextColumn::make('site'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(auth()->user()->role == 1 ? true : false),
                Tables\Actions\DeleteAction::make()->visible(auth()->user()->role == 1 ? true : false),
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
