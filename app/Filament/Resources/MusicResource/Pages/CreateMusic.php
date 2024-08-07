<?php

namespace App\Filament\Resources\MusicResource\Pages;

use Owenoj\LaravelGetId3\GetId3;
use Illuminate\Support\Facades\Storage;
use App\Filament\Resources\MusicResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Music;
use App\Models\Genre;
use falahati\PHPMP3\MpegAudio;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

class CreateMusic extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = MusicResource::class;

 protected function getSteps(): array
{
    return [
        Step::make('Tags')
            ->description('Add Track Details')
            ->schema([
                TextInput::make('artist')->required(),
                TextInput::make('title')->required(),
            ]),

            Step::make('Genre')
            ->description('Add Genre and Price')
            ->schema([
                Select::make('amount')->label('price')
                    ->options([
                        '8' => 'R8',
                        '10' => 'R10',
                        '12' => 'R12',
                        '15' => 'R15',
                        '20' => 'R20',
                    ]),

                Select::make('genre_id')
                    ->label('Genre')
                    ->options(Genre::all()->pluck('title', 'id'))
                    ->searchable(),
            ]),
        Step::make('Description')
            ->description('Add Short Note')
            ->schema([
                MarkdownEditor::make('description')
                    ->columnSpan('full'),
            ]),
        Step::make('Upload')
            ->description('Add Cover And Song ')
            ->schema([
                 FileUpload::make('image')->maxSize(512)
                 ->disk('public')->directory('images')
                 ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif']),
                 FileUpload::make('file')->preserveFilenames()
                      ->acceptedFileTypes(['audio/mpeg', 'audio/mp3'])->maxSize(10024),
            ]),
    ];
}
    protected function afterCreate()
{
    // Get the authenticated user
    $user = Auth::user();

    // Log the created record
    logger($this->record);

    // Get the file path
    $file = Storage::disk('public')->path($this->record->file);

    $track = new GetId3($file);
    $track->extractInfo();
    $duration = $track->getPlaytime();

    $filesizeInBytes = filesize($file);
    $filesizeInMB = round($filesizeInBytes / (1024 * 1024), 2);

    $filenameWithoutExtension = pathinfo($this->record->file, PATHINFO_FILENAME);

    $demoFilename = str_replace(' ', '-', $filenameWithoutExtension) . '-demo.mp3';
    MpegAudio::fromFile($file)->trim(10, 30)->saveFile(public_path('storage/demos/' . $demoFilename));
    $demoName = $demoFilename;

    $music = Music::findOrFail($this->record->id);

    $music->update([
        'duration' => $duration,
        'size' => $filesizeInMB,
        'demo' => $demoName,
    ]);

   $this->record->users()->attach($user);

    Notification::make()
        ->success()
        ->title('Music Uploaded!')
        ->body('30 seconds snippet generated Successfully!!')
        ->duration(9000)
        ->send();
}
}
