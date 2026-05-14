<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RedirectResource\Pages;
use App\Models\Redirect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Str;

class RedirectResource extends Resource
{
    protected static ?string $model = Redirect::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    
    protected static ?string $navigationGroup = 'Contenuti';
    
    protected static ?string $navigationLabel = 'Redirect';
    
    protected static ?string $modelLabel = 'Redirect';
    
    protected static ?string $pluralModelLabel = 'Redirect';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('source_url')
                    ->label('URL Origine')
                    ->required()
                    ->placeholder('/vecchia-pagina')
                    ->unique(ignoreRecord: true)
                    ->maxLength(500)
                    ->helperText('Percorso relativo (es: /vecchia-pagina) o URL completo'),
                    
                Forms\Components\TextInput::make('destination_url')
                    ->label('URL Destinazione')
                    ->required()
                    ->placeholder('/nuova-pagina')
                    ->maxLength(500)
                    ->helperText('Percorso relativo o URL completo (es: https://esempio.com)'),
                    
                Forms\Components\Select::make('status_code')
                    ->label('Codice di Stato')
                    ->required()
                    ->default(301)
                    ->options([
                        301 => '301 - Redirect Permanente',
                        302 => '302 - Redirect Temporaneo',
                    ]),
                    
                Forms\Components\Toggle::make('is_active')
                    ->label('Attivo')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('source_url')
                    ->label('URL Origine')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->source_url;
                    })
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('destination_url')
                    ->label('URL Destinazione')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->destination_url;
                    })
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('status_code')
                    ->label('Codice')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        301 => 'info',
                        302 => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (int $state): string => (string) $state),
                    
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Attivo'),
                    
                Tables\Columns\TextColumn::make('hits')
                    ->label('Utilizzi')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_code')
                    ->label('Codice di Stato')
                    ->options([
                        301 => '301 - Permanente',
                        302 => '302 - Temporaneo',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Attivo')
                    ->placeholder('Tutti')
                    ->trueLabel('Solo attivi')
                    ->falseLabel('Solo inattivi'),
            ])
            ->actions([
                Action::make('test')
                    ->label('Testa')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('info')
                    ->url(fn (Redirect $record): string => $record->source_url)
                    ->openUrlInNewTab(),
                    
                Action::make('reset_hits')
                    ->label('Reset Utilizzi')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Conferma reset')
                    ->modalDescription('Vuoi azzerare il contatore degli utilizzi per questo redirect?')
                    ->action(fn (Redirect $record) => $record->update(['hits' => 0])),
                    
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('reset_hits_bulk')
                        ->label('Reset Utilizzi')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['hits' => 0]);
                            });
                        }),
                ]),
            ])
            ->defaultSort('hits', 'desc');
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRedirects::route('/'),
            'create' => Pages\CreateRedirect::route('/create'),
            'edit' => Pages\EditRedirect::route('/{record}/edit'),
        ];
    }
}