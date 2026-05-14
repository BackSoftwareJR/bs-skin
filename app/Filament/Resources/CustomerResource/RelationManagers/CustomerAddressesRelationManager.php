<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Enums\CustomerAddressType;

class CustomerAddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    protected static ?string $title = 'Indirizzi Cliente';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informazioni Indirizzo')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Tipo')
                            ->required()
                            ->options(CustomerAddressType::class),
                            
                        Forms\Components\TextInput::make('full_name')
                            ->label('Nome Completo')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('company')
                            ->label('Azienda')
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('address_line_1')
                            ->label('Indirizzo')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('address_line_2')
                            ->label('Indirizzo (riga 2)')
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('city')
                            ->label('Città')
                            ->required()
                            ->maxLength(100),
                            
                        Forms\Components\TextInput::make('province')
                            ->label('Provincia')
                            ->required()
                            ->maxLength(10),
                            
                        Forms\Components\TextInput::make('postal_code')
                            ->label('CAP')
                            ->required()
                            ->maxLength(20),
                            
                        Forms\Components\TextInput::make('country')
                            ->label('Paese')
                            ->required()
                            ->default('IT')
                            ->maxLength(2),
                            
                        Forms\Components\TextInput::make('phone')
                            ->label('Telefono')
                            ->maxLength(20),
                            
                        Forms\Components\Toggle::make('is_default')
                            ->label('Indirizzo Predefinito')
                            ->default(false),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Dati Fatturazione')
                    ->schema([
                        Forms\Components\TextInput::make('vat_number')
                            ->label('Partita IVA')
                            ->maxLength(20),
                            
                        Forms\Components\TextInput::make('tax_code')
                            ->label('Codice Fiscale')
                            ->maxLength(20),
                            
                        Forms\Components\TextInput::make('sdi_code')
                            ->label('Codice SDI')
                            ->maxLength(10)
                            ->helperText('Codice per fatturazione elettronica'),
                            
                        Forms\Components\TextInput::make('pec')
                            ->label('PEC')
                            ->email()
                            ->maxLength(255)
                            ->helperText('Posta Elettronica Certificata'),
                    ])
                    ->collapsed()
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (CustomerAddressType $state): string => $state->getLabel())
                    ->color(fn (CustomerAddressType $state): string => $state->getColor()),
                    
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nome Completo')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('address_display')
                    ->label('Indirizzo')
                    ->formatStateUsing(function ($record) {
                        return trim($record->address_line_1 . ' ' . $record->address_line_2);
                    })
                    ->limit(30)
                    ->tooltip(function ($record) {
                        return $record->full_address;
                    }),
                    
                Tables\Columns\TextColumn::make('city')
                    ->label('Città')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('province')
                    ->label('Provincia')
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_default')
                    ->label('Predefinito')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options(CustomerAddressType::class),
                    
                Tables\Filters\TernaryFilter::make('is_default')
                    ->label('Predefinito')
                    ->placeholder('Tutti')
                    ->trueLabel('Solo predefiniti')
                    ->falseLabel('Solo non predefiniti'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nuovo Indirizzo'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('is_default', 'desc');
    }
}