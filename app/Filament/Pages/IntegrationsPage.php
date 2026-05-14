<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class IntegrationsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static string $view = 'filament.pages.integrations-page';
    
    protected static ?string $navigationGroup = 'Impostazioni';
    
    protected static ?string $navigationLabel = 'Integrazioni';
    
    protected static ?string $title = 'Gestione Integrazioni';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            // Placeholder per le settings esistenti
            'stripe_enabled' => false,
            'stripe_public_key' => '',
            'stripe_secret_key' => '',
            'paypal_enabled' => false,
            'paypal_client_id' => '',
            'paypal_client_secret' => '',
            'mailchimp_enabled' => false,
            'mailchimp_api_key' => '',
            'aruba_fic_enabled' => false,
            'aruba_fic_api_key' => '',
            'fatture_cloud_enabled' => false,
            'fatture_cloud_api_key' => '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Stripe')
                    ->description('Gestione pagamenti con Stripe')
                    ->icon('heroicon-o-credit-card')
                    ->schema([
                        Forms\Components\Toggle::make('stripe_enabled')
                            ->label('Abilita Stripe')
                            ->live(),
                            
                        Forms\Components\TextInput::make('stripe_public_key')
                            ->label('Chiave Pubblica')
                            ->placeholder('pk_live_...')
                            ->visible(fn (Forms\Get $get): bool => $get('stripe_enabled'))
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('stripe_secret_key')
                            ->label('Chiave Segreta')
                            ->placeholder('sk_live_...')
                            ->password()
                            ->visible(fn (Forms\Get $get): bool => $get('stripe_enabled'))
                            ->maxLength(255),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('PayPal')
                    ->description('Gestione pagamenti con PayPal')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Forms\Components\Toggle::make('paypal_enabled')
                            ->label('Abilita PayPal')
                            ->live(),
                            
                        Forms\Components\TextInput::make('paypal_client_id')
                            ->label('Client ID')
                            ->visible(fn (Forms\Get $get): bool => $get('paypal_enabled'))
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('paypal_client_secret')
                            ->label('Client Secret')
                            ->password()
                            ->visible(fn (Forms\Get $get): bool => $get('paypal_enabled'))
                            ->maxLength(255),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Mailchimp')
                    ->description('Gestione newsletter con Mailchimp')
                    ->icon('heroicon-o-envelope')
                    ->schema([
                        Forms\Components\Toggle::make('mailchimp_enabled')
                            ->label('Abilita Mailchimp')
                            ->live(),
                            
                        Forms\Components\TextInput::make('mailchimp_api_key')
                            ->label('API Key')
                            ->password()
                            ->visible(fn (Forms\Get $get): bool => $get('mailchimp_enabled'))
                            ->maxLength(255),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Aruba FIC')
                    ->description('Fatturazione elettronica con Aruba')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\Toggle::make('aruba_fic_enabled')
                            ->label('Abilita Aruba FIC')
                            ->live(),
                            
                        Forms\Components\TextInput::make('aruba_fic_api_key')
                            ->label('API Key')
                            ->password()
                            ->visible(fn (Forms\Get $get): bool => $get('aruba_fic_enabled'))
                            ->maxLength(255),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Fatture in Cloud')
                    ->description('Gestione fatture con Fatture in Cloud')
                    ->icon('heroicon-o-cloud')
                    ->schema([
                        Forms\Components\Toggle::make('fatture_cloud_enabled')
                            ->label('Abilita Fatture in Cloud')
                            ->live(),
                            
                        Forms\Components\TextInput::make('fatture_cloud_api_key')
                            ->label('API Key')
                            ->password()
                            ->visible(fn (Forms\Get $get): bool => $get('fatture_cloud_enabled'))
                            ->maxLength(255),
                    ])
                    ->collapsible(),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Salva Configurazione')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            
            // Qui salveremmo le impostazioni nel database o in un file di configurazione
            // Per ora è un placeholder
            
            Notification::make()
                ->success()
                ->title('Configurazione salvata')
                ->body('Le impostazioni delle integrazioni sono state salvate con successo.')
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Errore nel salvataggio')
                ->body('Impossibile salvare le impostazioni: ' . $e->getMessage())
                ->send();
        }
    }

    public function testConnection(string $integration): void
    {
        try {
            $data = $this->form->getState();
            
            // Placeholder per test connessioni
            $isValid = match ($integration) {
                'stripe' => !empty($data['stripe_public_key']) && !empty($data['stripe_secret_key']),
                'paypal' => !empty($data['paypal_client_id']) && !empty($data['paypal_client_secret']),
                'mailchimp' => !empty($data['mailchimp_api_key']),
                'aruba_fic' => !empty($data['aruba_fic_api_key']),
                'fatture_cloud' => !empty($data['fatture_cloud_api_key']),
                default => false,
            };

            if ($isValid) {
                Notification::make()
                    ->success()
                    ->title('Connessione valida')
                    ->body("Le credenziali per {$integration} sembrano valide.")
                    ->send();
            } else {
                Notification::make()
                    ->warning()
                    ->title('Credenziali mancanti')
                    ->body("Inserisci tutte le credenziali richieste per {$integration}.")
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Errore nel test')
                ->body('Impossibile testare la connessione: ' . $e->getMessage())
                ->send();
        }
    }
}