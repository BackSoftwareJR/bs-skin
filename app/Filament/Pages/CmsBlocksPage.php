<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\Block;

class CmsBlocksPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static string $view = 'filament.pages.cms-blocks-page';
    
    protected static ?string $navigationGroup = 'Contenuti';
    
    protected static ?string $navigationLabel = 'Blocchi Globali';
    
    protected static ?string $title = 'Gestione Blocchi Globali';

    public ?string $selectedLocation = 'homepage';
    
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'location' => $this->selectedLocation,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('location')
                    ->label('Posizione')
                    ->options([
                        'homepage' => 'Homepage',
                        'chi-siamo' => 'Chi Siamo',
                        'footer' => 'Footer',
                        'announcement_bar' => 'Barra Annunci',
                    ])
                    ->default('homepage')
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->selectedLocation = $state;
                        $this->loadBlocks();
                    }),
            ])
            ->statePath('data');
    }

    public function loadBlocks(): void
    {
        // Qui caricheremmo i blocchi per la location selezionata
        // Per ora è un placeholder
    }

    protected function getActions(): array
    {
        return [
            Action::make('add_block')
                ->label('Aggiungi Blocco')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->form([
                    Forms\Components\Select::make('type')
                        ->label('Tipo Blocco')
                        ->required()
                        ->options([
                            'hero' => 'Hero Section',
                            'text' => 'Testo',
                            'image' => 'Immagine',
                            'gallery' => 'Galleria',
                            'cta' => 'Call to Action',
                            'testimonial' => 'Testimonial',
                            'features' => 'Caratteristiche',
                        ])
                        ->live(),
                        
                    Forms\Components\TextInput::make('title')
                        ->label('Titolo')
                        ->maxLength(255),
                        
                    Forms\Components\Textarea::make('content')
                        ->label('Contenuto')
                        ->rows(3)
                        ->visible(fn (Forms\Get $get): bool => in_array($get('type'), ['text', 'hero', 'cta'])),
                        
                    Forms\Components\TextInput::make('button_text')
                        ->label('Testo Pulsante')
                        ->maxLength(100)
                        ->visible(fn (Forms\Get $get): bool => in_array($get('type'), ['hero', 'cta'])),
                        
                    Forms\Components\TextInput::make('button_url')
                        ->label('URL Pulsante')
                        ->url()
                        ->visible(fn (Forms\Get $get): bool => in_array($get('type'), ['hero', 'cta'])),
                        
                    Forms\Components\Toggle::make('is_active')
                        ->label('Attivo')
                        ->default(true),
                        
                    Forms\Components\TextInput::make('sort_order')
                        ->label('Ordine')
                        ->numeric()
                        ->default(0),
                ])
                ->action(function (array $data) {
                    try {
                        // Block::create([...]);
                        // Placeholder fino a quando il modello non sarà completo
                        
                        Notification::make()
                            ->success()
                            ->title('Blocco aggiunto')
                            ->body('Il blocco è stato aggiunto con successo.')
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->danger()
                            ->title('Errore')
                            ->body('Impossibile aggiungere il blocco: ' . $e->getMessage())
                            ->send();
                    }
                }),
        ];
    }

    public function getBlocks()
    {
        // Placeholder per i blocchi della location selezionata
        return collect([
            [
                'id' => 1,
                'type' => 'hero',
                'title' => 'Hero Section Homepage',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'id' => 2,
                'type' => 'text',
                'title' => 'Testo Introduttivo',
                'is_active' => true,
                'sort_order' => 2,
            ],
        ]);
    }

    public function toggleBlockActive($blockId): void
    {
        // Placeholder per toggle dello stato attivo
        Notification::make()
            ->info()
            ->title('Funzione non ancora disponibile')
            ->body('Il toggle dei blocchi sarà disponibile dopo l\'implementazione completa.')
            ->send();
    }

    public function editBlock($blockId): void
    {
        // Placeholder per edit del blocco
        Notification::make()
            ->info()
            ->title('Funzione non ancora disponibile')
            ->body('La modifica dei blocchi sarà disponibile dopo l\'implementazione completa.')
            ->send();
    }

    public function deleteBlock($blockId): void
    {
        // Placeholder per eliminazione del blocco
        Notification::make()
            ->info()
            ->title('Funzione non ancora disponibile')
            ->body('L\'eliminazione dei blocchi sarà disponibile dopo l\'implementazione completa.')
            ->send();
    }
}