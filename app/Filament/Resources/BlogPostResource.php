<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = 'Articoli Blog';
    
    protected static ?string $modelLabel = 'articolo';
    
    protected static ?string $pluralModelLabel = 'articoli';
    
    protected static ?string $navigationGroup = 'Contenuti';
    
    protected static ?int $navigationSort = 30;
    
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Blog Post')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Contenuto')
                            ->schema([
                                Forms\Components\Section::make('Informazioni Base')
                                    ->schema([
                                        Forms\Components\Select::make('blog_category_id')
                                            ->label('Categoria')
                                            ->relationship('category', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload(),
                                        
                                        Forms\Components\TextInput::make('title')
                                            ->label('Titolo')
                                            ->required()
                                            ->maxLength(255)
                                            ->reactive()
                                            ->afterStateUpdated(fn ($state, callable $set) => 
                                                $set('slug', str()->slug($state))
                                            ),
                                        
                                        Forms\Components\TextInput::make('slug')
                                            ->label('Slug URL')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->rules(['regex:/^[a-z0-9\-]+$/']),
                                        
                                        Forms\Components\Select::make('author_user_id')
                                            ->label('Autore')
                                            ->relationship('author', 'name')
                                            ->required()
                                            ->default(auth()->id()),
                                    ])
                                    ->columns(2),
                                
                                Forms\Components\Section::make('Contenuto Articolo')
                                    ->schema([
                                        Forms\Components\Textarea::make('excerpt')
                                            ->label('Estratto')
                                            ->required()
                                            ->rows(3)
                                            ->maxLength(300)
                                            ->columnSpanFull(),
                                        
                                        Forms\Components\RichEditor::make('body_html')
                                            ->label('Contenuto')
                                            ->required()
                                            ->columnSpanFull(),
                                    ]),
                                
                                Forms\Components\Section::make('Pubblicazione')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_published')
                                            ->label('Pubblicato')
                                            ->reactive(),
                                        
                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->label('Data Pubblicazione')
                                            ->default(now())
                                            ->visible(fn (Forms\Get $get): bool => $get('is_published')),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Media e Tags')
                            ->schema([
                                Forms\Components\Section::make('Immagine in Evidenza')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('featured_image')
                                            ->label('Immagine in Evidenza')
                                            ->collection('featured_image')
                                            ->image()
                                            ->imageEditor()
                                            ->required(),
                                    ]),
                                
                                Forms\Components\Section::make('Tags')
                                    ->schema([
                                        Forms\Components\CheckboxList::make('tags')
                                            ->label('Tags')
                                            ->relationship('tags', 'name')
                                            ->columns(3),
                                    ]),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\Section::make('Meta Tags')
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
                                    ])
                                    ->columns(1),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('featured_image')
                    ->label('Immagine')
                    ->collection('featured_image')
                    ->width(60)
                    ->height(60),
                
                Tables\Columns\TextColumn::make('title')
                    ->label('Titolo')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoria')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Autore')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Pubblicato')
                    ->boolean()
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash')
                    ->trueColor('success')
                    ->falseColor('gray'),
                
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Data Pubblicazione')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('view_count')
                    ->label('Visualizzazioni')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creato')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('blog_category_id')
                    ->label('Categoria')
                    ->relationship('category', 'name'),
                
                Tables\Filters\SelectFilter::make('author_user_id')
                    ->label('Autore')
                    ->relationship('author', 'name'),
                
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Stato Pubblicazione')
                    ->trueLabel('Solo pubblicati')
                    ->falseLabel('Solo bozze'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Pubblica')
                        ->icon('heroicon-o-eye')
                        ->action(function (Collection $records) {
                            $records->each->update([
                                'is_published' => true,
                                'published_at' => now(),
                            ]);
                        }),
                    
                    Tables\Actions\BulkAction::make('unpublish')
                        ->label('Rimuovi Pubblicazione')
                        ->icon('heroicon-o-eye-slash')
                        ->action(fn (Collection $records) => 
                            $records->each->update(['is_published' => false])
                        ),
                    
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'view' => Pages\ViewBlogPost::route('/{record}'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}