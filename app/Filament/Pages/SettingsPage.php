<?php

namespace App\Filament\Pages;

use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification;

class SettingsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.settings-page';
    
    protected static ?string $navigationLabel = 'Impostazioni';
    
    protected static ?string $title = 'Impostazioni Sistema';
    
    protected static ?string $navigationGroup = 'Impostazioni';
    
    protected static ?int $navigationSort = 10;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            // Generale
            'site_name' => setting('site_name', 'SkinTemple'),
            'site_tagline' => setting('site_tagline', 'Beauty & Wellness'),
            'contact_email' => setting('contact_email'),
            'contact_phone' => setting('contact_phone'),
            'company_name' => setting('company_name'),
            'company_vat' => setting('company_vat'),
            'company_address' => setting('company_address'),
            
            // E-commerce
            'currency' => setting('currency', 'EUR'),
            'tax_rate_default' => setting('tax_rate_default', 22),
            'prices_include_tax' => setting('prices_include_tax', true),
            'min_order_amount' => setting('min_order_amount', 0),
            'free_shipping_threshold' => setting('free_shipping_threshold', 50),
            'default_shipping_cost' => setting('default_shipping_cost', 5.90),
            'order_number_prefix' => setting('order_number_prefix', 'ST'),
            'invoice_number_prefix' => setting('invoice_number_prefix', 'FT'),
            
            // Email
            'from_address' => setting('mail_from_address'),
            'from_name' => setting('mail_from_name'),
            'reply_to' => setting('mail_reply_to'),
            
            // Notifiche Admin
            'admin_emails' => setting('admin_emails', []),
            'notify_new_order' => setting('notify_new_order', true),
            'notify_low_stock' => setting('notify_low_stock', true),
            'notify_failed_payment' => setting('notify_failed_payment', true),
            'low_stock_threshold' => setting('low_stock_threshold', 10),
            
            // Integrazioni
            'stripe_enabled' => setting('stripe_enabled', false),
            'paypal_enabled' => setting('paypal_enabled', false),
            'mailchimp_enabled' => setting('mailchimp_enabled', false),
            
            // Funzionalità
            'blog_enabled' => setting('blog_enabled', true),
            'wishlist_enabled' => setting('wishlist_enabled', true),
            'reviews_enabled' => setting('reviews_enabled', false),
            'b2b_pricing_enabled' => setting('b2b_pricing_enabled', false),
            'multilingual_enabled' => setting('multilingual_enabled', false),
            'dark_mode_enabled' => setting('dark_mode_enabled', false),
            'einvoice_badge' => setting('einvoice_badge', true),
            'einvoice_badge_label' => setting('einvoice_badge_label', 'Fatturazione Elettronica'),
            
            // SEO
            'default_meta_title' => setting('default_meta_title'),
            'default_meta_description' => setting('default_meta_description'),
            'google_site_verification' => setting('google_site_verification'),
            'robots_txt' => setting('robots_txt', "User-agent: *\nAllow: /"),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Generale')
                            ->schema([
                                Forms\Components\Section::make('Informazioni Sito')
                                    ->schema([
                                        Forms\Components\TextInput::make('site_name')
                                            ->label('Nome Sito')
                                            ->required(),
                                        
                                        Forms\Components\TextInput::make('site_tagline')
                                            ->label('Tagline')
                                            ->maxLength(100),
                                        
                                        Forms\Components\TextInput::make('contact_email')
                                            ->label('Email di Contatto')
                                            ->email()
                                            ->required(),
                                        
                                        Forms\Components\TextInput::make('contact_phone')
                                            ->label('Telefono')
                                            ->tel(),
                                    ])
                                    ->columns(2),
                                
                                Forms\Components\Section::make('Informazioni Aziendali')
                                    ->schema([
                                        Forms\Components\TextInput::make('company_name')
                                            ->label('Ragione Sociale')
                                            ->required(),
                                        
                                        Forms\Components\TextInput::make('company_vat')
                                            ->label('Partita IVA')
                                            ->mask('99999999999'),
                                        
                                        Forms\Components\Textarea::make('company_address')
                                            ->label('Indirizzo Completo')
                                            ->rows(3),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('E-commerce')
                            ->schema([
                                Forms\Components\Section::make('Valute e Prezzi')
                                    ->schema([
                                        Forms\Components\Select::make('currency')
                                            ->label('Valuta')
                                            ->options(['EUR' => 'Euro (€)', 'USD' => 'Dollaro ($)'])
                                            ->default('EUR'),
                                        
                                        Forms\Components\TextInput::make('tax_rate_default')
                                            ->label('Aliquota IVA Default (%)')
                                            ->numeric()
                                            ->suffix('%')
                                            ->default(22),
                                        
                                        Forms\Components\Toggle::make('prices_include_tax')
                                            ->label('Prezzi IVA inclusa'),
                                    ])
                                    ->columns(3),
                                
                                Forms\Components\Section::make('Ordini e Spedizioni')
                                    ->schema([
                                        Forms\Components\TextInput::make('min_order_amount')
                                            ->label('Importo Minimo Ordine')
                                            ->numeric()
                                            ->prefix('€')
                                            ->default(0),
                                        
                                        Forms\Components\TextInput::make('free_shipping_threshold')
                                            ->label('Soglia Spedizione Gratuita')
                                            ->numeric()
                                            ->prefix('€')
                                            ->default(50),
                                        
                                        Forms\Components\TextInput::make('default_shipping_cost')
                                            ->label('Costo Spedizione Standard')
                                            ->numeric()
                                            ->prefix('€')
                                            ->step(0.01)
                                            ->default(5.90),
                                        
                                        Forms\Components\TextInput::make('order_number_prefix')
                                            ->label('Prefisso Numero Ordine')
                                            ->default('ST')
                                            ->maxLength(5),
                                        
                                        Forms\Components\TextInput::make('invoice_number_prefix')
                                            ->label('Prefisso Numero Fattura')
                                            ->default('FT')
                                            ->maxLength(5),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Email')
                            ->schema([
                                Forms\Components\Section::make('Configurazione Email')
                                    ->schema([
                                        Forms\Components\TextInput::make('from_address')
                                            ->label('Indirizzo Mittente')
                                            ->email()
                                            ->required(),
                                        
                                        Forms\Components\TextInput::make('from_name')
                                            ->label('Nome Mittente')
                                            ->required(),
                                        
                                        Forms\Components\TextInput::make('reply_to')
                                            ->label('Reply To')
                                            ->email(),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Notifiche Admin')
                            ->schema([
                                Forms\Components\Section::make('Email Amministratori')
                                    ->schema([
                                        Forms\Components\Repeater::make('admin_emails')
                                            ->label('Email Amministratori')
                                            ->schema([
                                                Forms\Components\TextInput::make('email')
                                                    ->email()
                                                    ->required(),
                                            ])
                                            ->defaultItems(1)
                                            ->columnSpanFull(),
                                    ]),
                                
                                Forms\Components\Section::make('Tipi di Notifica')
                                    ->schema([
                                        Forms\Components\Toggle::make('notify_new_order')
                                            ->label('Nuovo Ordine'),
                                        
                                        Forms\Components\Toggle::make('notify_low_stock')
                                            ->label('Stock Basso'),
                                        
                                        Forms\Components\Toggle::make('notify_failed_payment')
                                            ->label('Pagamento Fallito'),
                                        
                                        Forms\Components\TextInput::make('low_stock_threshold')
                                            ->label('Soglia Stock Basso')
                                            ->numeric()
                                            ->default(10),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Funzionalità')
                            ->schema([
                                Forms\Components\Section::make('Moduli Sistema')
                                    ->schema([
                                        Forms\Components\Toggle::make('blog_enabled')
                                            ->label('Blog'),
                                        
                                        Forms\Components\Toggle::make('wishlist_enabled')
                                            ->label('Lista Desideri'),
                                        
                                        Forms\Components\Toggle::make('reviews_enabled')
                                            ->label('Recensioni Prodotti'),
                                        
                                        Forms\Components\Toggle::make('b2b_pricing_enabled')
                                            ->label('Prezzi B2B'),
                                        
                                        Forms\Components\Toggle::make('multilingual_enabled')
                                            ->label('Multilingua'),
                                        
                                        Forms\Components\Toggle::make('dark_mode_enabled')
                                            ->label('Dark Mode'),
                                    ])
                                    ->columns(3),
                                
                                Forms\Components\Section::make('Badge e Etichette')
                                    ->schema([
                                        Forms\Components\Toggle::make('einvoice_badge')
                                            ->label('Badge Fatturazione Elettronica'),
                                        
                                        Forms\Components\TextInput::make('einvoice_badge_label')
                                            ->label('Testo Badge')
                                            ->default('Fatturazione Elettronica'),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\Section::make('Meta Tags Default')
                                    ->schema([
                                        Forms\Components\TextInput::make('default_meta_title')
                                            ->label('Meta Title Default')
                                            ->maxLength(60),
                                        
                                        Forms\Components\Textarea::make('default_meta_description')
                                            ->label('Meta Description Default')
                                            ->rows(3)
                                            ->maxLength(160),
                                        
                                        Forms\Components\TextInput::make('google_site_verification')
                                            ->label('Google Site Verification')
                                            ->helperText('Codice per Google Search Console'),
                                        
                                        Forms\Components\Textarea::make('robots_txt')
                                            ->label('Contenuto robots.txt')
                                            ->rows(5)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            
            // Salva ogni setting individualmente
            foreach ($data as $key => $value) {
                setting([$key => $value]);
            }
            
            Notification::make()
                ->success()
                ->title('Impostazioni salvate')
                ->body('Le impostazioni sono state aggiornate correttamente.')
                ->send();
                
        } catch (Halt $exception) {
            return;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Salva Impostazioni')
                ->action('save'),
        ];
    }
}