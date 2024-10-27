<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeatResource\Pages;
use App\Filament\Resources\beatResource\RelationManagers;
use App\Models\Beat;
use App\Models\Genre;
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
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Step;
use Illuminate\Support\HtmlString;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class BeatResource extends Resource
{
    protected static ?string $model = Beat::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Products';

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('artist'),
                        TextInput::make('title'),
                        Select::make('amount')->label('price')
                            ->options([
                                '60' => 'R60',
                                '70' => 'R70',
                                '100' => 'R100',
                                '140' => 'R140',
                            ]),

                        Select::make('genre_id')
                            ->label('Genre')
                            ->options(Genre::all()->pluck('title', 'id'))
                            ->searchable(),
                        MarkdownEditor::make('description')->columnSpanFull(),
                        FileUpload::make('image')->maxSize(512)->disk('public')->directory('images'),
                        FileUpload::make('file')->preserveFilenames()
                            ->acceptedFileTypes(['audio/mpeg', 'audio/mp3'])->maxSize(13024),


                    ])->Columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();

        $query = Beat::query();

        // Filter records based on the authenticated user's role and ownership of music records
        if ($user && $user->role != 1) {
            // Join with the pivot table and filter records based on the user's ID
            $query->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        // Check if the authenticated user's role is 1 (admin)
        if ($user && $user->role == 1) {
            // Add bulk actions for admin users
            $bulkActions = [
                Tables\Actions\DeleteBulkAction::make(),
            ];
            $table->bulkActions($bulkActions);
        }

        return $table
            ->query($query)
            ->columns([
                ImageColumn::make('image')->size(30),
                TextColumn::make('title'),
                TextColumn::make('artist'),
                TextColumn::make('genre.title'),
            ])
            ->filters([
                //
            ])
            ->actions([
    Tables\Actions\ViewAction::make(),
    Tables\Actions\EditAction::make(),
    Tables\Actions\DeleteAction::make()
        ->before(function ($record) {
            // You can perform operations before the delete action here
        })
        ->after(function ($record) {
            // Perform operations after the delete action here
            if ($record->image) {
                Storage::disk('public')->delete($record->image);
                if (!Storage::disk('public')->exists($record->image)) {
                    Log::info("Image '{$record->image}' deleted successfully.");
                } else {
                    Log::warning("Failed to delete image '{$record->image}'.");
                }
            }
            if ($record->file) {
                Storage::disk('public')->delete($record->file);
                if (!Storage::disk('public')->exists($record->file)) {
                    Log::info("File '{$record->file}' deleted successfully.");
                } else {
                    Log::warning("Failed to delete file '{$record->file}'.");
                }
            }
            if ($record->demo) {
                Storage::disk('public')->delete("demos/{$record->demo}");
                if (!Storage::disk('public')->exists("demos/{$record->demo}")) {
                    Log::info("Demo '{$record->demo}' deleted successfully.");
                } else {
                    Log::warning("Failed to delete demo '{$record->demo}'.");
                }
            }
        }),
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
            'index' => Pages\ListBeat::route('/'),
            'create' => Pages\CreateBeat::route('/create'),
            'edit' => Pages\EditBeat::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    // public static function getGloballySearchableAttributes(): array
    // {
    //     return ['title', 'artist'];
    // }
}
