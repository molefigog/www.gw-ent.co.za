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

        // Query filtering based on user role
        $query = Music::query();
        if ($user && $user->role != 1) {
            $query->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        // Conditionally add bulk actions for admin users
        if ($user && $user->role == 1) {
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

                // Conditionally display 'sold' column only if 'beat' is true for the record
                TextColumn::make('sold')
                ->label('Sold')
                ->formatStateUsing(fn ($state) => $state ? 'True' : 'False'),

                TextColumn::make('beat')
                ->label('is_beat')
                ->formatStateUsing(fn ($state) => $state ? 'True' : 'False'),
                // Conditionally display 'publish' column only if 'beat' is true for the record
                CheckboxColumn::make('publish')
                    ->label('Published')

            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        // Custom logic before deletion
                    })
                    ->after(function ($record) {
                        // After deletion logic to delete associated files
                        if ($record->image) {
                            Storage::disk('public')->delete($record->image);
                            Log::info(Storage::disk('public')->exists($record->image)
                                ? "Failed to delete image '{$record->image}'."
                                : "Image '{$record->image}' deleted successfully.");
                        }
                        if ($record->file) {
                            Storage::disk('public')->delete($record->file);
                            Log::info(Storage::disk('public')->exists($record->file)
                                ? "Failed to delete file '{$record->file}'."
                                : "File '{$record->file}' deleted successfully.");
                        }
                        if ($record->demo) {
                            Storage::disk('public')->delete("demos/{$record->demo}");
                            Log::info(Storage::disk('public')->exists("demos/{$record->demo}")
                                ? "Failed to delete demo '{$record->demo}'."
                                : "Demo '{$record->demo}' deleted successfully.");
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
