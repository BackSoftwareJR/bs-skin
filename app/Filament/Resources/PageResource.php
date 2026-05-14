<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';
    
    protected static ?string $navigationLabel = 'Pagine';
    
    protected static ?string $modelLabel = 'pagina';
    
    protected static ?string $pluralModelLabel = 'pagine';
    
    protected static ?string $navigationGroup = 'Contenuti';
    
    protected static ?int $navigationSort = 10;
    
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informazioni Base')
                    ->schema([
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->rules(['regex:/^[a-z0-9\-\/]+$/']),
                        
                        Forms\Components\TextInput::make('title')
                            ->label('Titolo Pagina')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('template')
                            ->label('Template')
                            ->options([
                                'default' => 'Default',
                                'home' => 'Homepage',
                                'about' => 'Chi Siamo',
                                'contact' => 'Contatti',
                                'privacy' => 'Privacy Policy',
                                'terms' => 'Termini e Condizioni',
                                'faq' => 'FAQ',
                                'custom' => 'Personalizzato',
                            ])
                            ->required()
                            ->native(false),
                        
                        Forms\Components\Toggle::make('is_published')
                            ->label('Pubblicata')
                            ->default(true),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(60),
                        
                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->maxLength(160),
                        
                        Forms\Components\TextInput::make('og_title')
                            ->label('OG Title (Facebook)')
                            ->maxLength(60),
                        
                        Forms\Components\Textarea::make('og_description')
                            ->label('OG Description (Facebook)')
                            ->rows(2)
                            ->maxLength(160),
                        
                        SpatieMediaLibraryFileUpload::make('og_image')
                            ->label('OG Image')
                            ->collection('og_image')
                            ->image(),
                        
                        Forms\Components\TextInput::make('canonical_url')
                            ->label('Canonical URL')
                            ->url(),
                        
                        Forms\Components\Select::make('robots')
                            ->label('Robots')
                            ->options([
                                'index,follow' => 'index,follow',
                                'noindex,follow' => 'noindex,follow',
                                'index,nofollow' => 'index,nofollow',
                                'noindex,nofollow' => 'noindex,nofollow',
                            ])
                            ->default('index,follow'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Titolo')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('slug')
                    ->label('URL')
                    ->searchable()
                    ->copyable()
                    ->formatStateUsing(fn (string $state): string => "/{$state}"),
                
                Tables\Columns\TextColumn::make('template')
                    ->label('Template')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Pubblicata')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('blocks_count')
                    ->label('Blocchi')
                    ->counts('blocks')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Aggiornata')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('template')
                    ->label('Template')
                    ->options([
                        'default' => 'Default',
                        'home' => 'Homepage',
                        'about' => 'Chi Siamo',
                        'contact' => 'Contatti',
                        'privacy' => 'Privacy Policy',
                        'terms' => 'Termini e Condizioni',
                        'faq' => 'FAQ',
                        'custom' => 'Personalizzato',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Stato Pubblicazione')
                    ->trueLabel('Solo pubblicate')
                    ->falseLabel('Solo bozze'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\Action::make('preview')
                    ->label('Anteprima')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Page $record): string => route('public.page', $record->slug))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Pubblica')
                        ->icon('heroicon-o-eye')
                        ->action(fn (Collection $records) => 
                            $records->each->update(['is_published' => true])
                        ),
                    
                    Tables\Actions\BulkAction::make('unpublish')
                        ->label('Rimuovi Pubblicazione')
                        ->icon('heroicon-o-eye-slash')
                        ->action(fn (Collection $records) => 
                            $records->each->update(['is_published' => false])
                        ),
                    
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BlocksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'view' => Pages\ViewPage::route('/{record}'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}