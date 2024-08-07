<?php

namespace App\Livewire;

use App\Models\Genre;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Step;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

use Livewire\Component;

class Form extends Component implements HasForms
{
    use InteractsWithForms;

    public $form;
    public $artist;
    public $title;
    public $amount;
    public $genre_id;
    public $description;
    public $image;
    public $file;

    public function mount(): void
    {
        $this->form = $this->makeForm();
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('Track Info')
                    ->schema([
                        TextInput::make('artist'),
                        TextInput::make('title'),
                        TextInput::make('amount')->numeric(),
                    ]),

                Wizard\Step::make('Details')
                    ->schema([
                        Select::make('genre_id')
                            ->label('Genre')
                            ->options(Genre::all()->pluck('title', 'id'))
                            ->searchable(),
                        TextInput::make('description'),
                    ]),

                Wizard\Step::make('Cover & Mp3')
                    ->schema([
                        FileUpload::make('image')->preserveFilenames()->image()->imageEditor(),
                        FileUpload::make('file')->preserveFilenames()
                            ->acceptedFileTypes(['audio/mpeg', 'audio/mp3']),
                    ]),
            ]),
        ];
    }

    public function render()
    {
        return view('livewire.form');
    }
}
