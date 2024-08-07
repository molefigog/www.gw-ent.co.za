<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Resource;
use Filament\Forms\Set;
use Illuminate\Support\Str;
// use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Step;
use Illuminate\Support\HtmlString;
use Filament\Tables;
use Filament\Tables\Table;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
     protected static ?string $modelLabel = 'Posts';
    protected static ?string $navigationGroup = 'Blog';

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        return $form
            ->schema([
                Card::make()
                ->schema([
                    TextInput::make('name')->live()
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    TextInput::make('slug')->readonly(),

                Select::make('category_name')
                    ->label('Category')
                    ->options(Category::all()->pluck('title', 'id'))
                    ->searchable(),
                TinyEditor::make('detail')->columnSpanFull(),
             ])->Columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //  ImageColumn::make('image')->size(30),
                TextColumn::make('name'),
                // TextColumn::make('artist'),
                TextColumn::make('category.title'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
