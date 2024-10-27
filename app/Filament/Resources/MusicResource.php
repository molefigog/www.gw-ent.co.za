<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MusicResource\Pages;
use App\Filament\Resources\MusicResource\RelationManagers;
use App\Models\Music;
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
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Step;
use Illuminate\Support\HtmlString;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Columns\CheckboxColumn;

class MusicResource extends Resource
{


    protected static ?string $model = Music::class;

    protected static ?string $navigationIcon = 'heroicon-o-musical-note';
    protected static ?string $navigationGroup = 'Products';

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('artist')->required(),
                        TextInput::make('title')->required(),
                        Select::make('amount')->label('price')->required()
                            ->options([
                                '8' => 'R8',
                                '10' => 'R10',
                                '12' => 'R12',
                                '15' => 'R15',
                            ]),

                        Select::make('genre_id')->required()
                            ->label('Genre')
                            ->options(Genre::all()->pluck('title', 'id'))
                            ->searchable(),
                        MarkdownEditor::make('description')->required()->columnSpanFull(),
                        FileUpload::make('image')->maxSize(512)->required()
                            ->disk('public')->directory('images')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif']),
                        FileUpload::make('file')->preserveFilenames()->required()
                            ->acceptedFileTypes(['audio/mpeg', 'audio/mp3'])->maxSize(10024),

                        ToggleButtons::make('beat')
                            ->label('is it a Beat?')

                            ->boolean()
                            ->grouped(),
                        ToggleButtons::make('free')
                            ->label('is it free?')

                            ->boolean()
                            ->grouped(),
                        ToggleButtons::make('publish')
                            ->label('Publish this song?')

                            ->boolean()
                            ->grouped(),
                    ])->Columns(2),

            ]);
    }




    public static function table(Table $table): Table
    {
        $user = Auth::user();

        $query = Music::query();

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
                TextColumn::make('title')->searchable(),
                TextColumn::make('artist')->searchable(),
                TextColumn::make('genre.title'),
                CheckboxColumn::make('publish'),
            ])
            ->filters([
                Filter::make('title')
                    ->label('Newest First')
                    ->query(function ($query) {
                        return $query->where('title')->orderBy('created_at', 'desc');
                    }),
                Filter::make('title1')
                    ->label('Oldest First')
                    ->query(function ($query) {
                        return $query->where('title')->orderBy('created_at', 'asc');
                    })
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
            'index' => Pages\ListMusic::route('/'),
            'create' => Pages\CreateMusic::route('/create'),
            'edit' => Pages\EditMusic::route('/{record}/edit'),

        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    // public static function getGloballySearchableAttributes(): array
    // {

    //     return ['title', 'artist', 'description'];
    // }

    // public static function getGlobalSearchResultDetails(Model $record): array
    // {
    //     return [
    //         "Artist" => $record->artist,
    //         "Title" => $record->title,
    //     ];
    // }
}
