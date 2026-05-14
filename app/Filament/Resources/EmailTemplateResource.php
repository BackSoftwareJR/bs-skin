<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailTemplateResource\Pages;
use App\Models\EmailTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmailTemplateResource extends Resource
{
    protected static ?string $model = EmailTemplate::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationGroup = 'Impostazioni';
    
    protected static ?string $navigationLabel = 'Template Email';
    
    protected static ?string $modelLabel = 'Template Email';
    
    protected static ?string $pluralModelLabel = 'Template Email';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('subject_template')
                    ->label('Oggetto Template')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Usa {{variabile}} per variabili dinamiche'),
                    
                Forms\Components\RichEditor::make('body_html_template')
                    ->label('Corpo HTML Template')
                    ->required()
                    ->helperText('Usa {{variabile}} per variabili dinamiche'),
                    
                Forms\Components\Toggle::make('is_active')
                    ->label('Attivo')
                    ->default(true),
                    
                Forms\Components\Placeholder::make('available_variables')
                    ->label('Variabili Disponibili')
                    ->content(function ($record) {
                        if (!$record || !$record->available_variables_json) {
                            return 'Nessuna variabile disponibile';
                        }
                        
                        $variables = json_decode($record->available_variables_json, true);
                        if (!is_array($variables)) {
                            return 'Formato variabili non valido';
                        }
                        
                        return collect($variables)->map(function ($description, $variable) {
                            return "{{" . $variable . "}} - " . $description;
                        })->join(', ');
                    })
                    ->helperText('Variabili che puoi usare nel template'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Codice')
                    ->badge()
                    ->fontFamily('mono')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Attivo')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Attivo')
                    ->placeholder('Tutti')
                    ->trueLabel('Solo attivi')
                    ->falseLabel('Solo inattivi'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // No bulk actions per i template
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmailTemplates::route('/'),
            'edit' => Pages\EditEmailTemplate::route('/{record}/edit'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false; // I template sono pre-seeded
    }
}