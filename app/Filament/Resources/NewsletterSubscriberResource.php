<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterSubscriberResource\Pages;
use App\Models\NewsletterSubscriber;
use App\Enums\NewsletterStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class NewsletterSubscriberResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    
    protected static ?string $navigationGroup = 'Marketing';
    
    protected static ?string $navigationLabel = 'Newsletter';
    
    protected static ?string $modelLabel = 'Iscritto';
    
    protected static ?string $pluralModelLabel = 'Iscritti Newsletter';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->maxLength(255),
                    
                Forms\Components\Select::make('status')
                    ->label('Stato')
                    ->options(NewsletterStatus::class)
                    ->disabled(fn ($record) => $record === null)
                    ->helperText('Solo per disiscrizioni manuali'),
                    
                Forms\Components\Textarea::make('note')
                    ->label('Note')
                    ->rows(3)
                    ->maxLength(1000),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable()
                    ->placeholder('Non specificato'),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Stato')
                    ->badge()
                    ->formatStateUsing(fn (NewsletterStatus $state): string => $state->getLabel())
                    ->color(fn (NewsletterStatus $state): string => match ($state) {
                        NewsletterStatus::SUBSCRIBED => 'success',
                        NewsletterStatus::PENDING => 'warning',
                        NewsletterStatus::UNSUBSCRIBED => 'gray',
                        NewsletterStatus::BOUNCED => 'danger',
                    }),
                    
                Tables\Columns\TextColumn::make('source')
                    ->label('Fonte')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('subscribed_at')
                    ->label('Iscritto il')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('external_subscriber_id')
                    ->label('ID Esterno')
                    ->placeholder('--')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Stato')
                    ->options(NewsletterStatus::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Modifica'),
                    
                Action::make('unsubscribe')
                    ->label('Disiscrivi')
                    ->icon('heroicon-o-x-mark')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Conferma disiscrizione')
                    ->modalDescription('Vuoi davvero disiscrivere questo utente dalla newsletter?')
                    ->action(function (NewsletterSubscriber $record) {
                        $record->update(['status' => NewsletterStatus::UNSUBSCRIBED]);
                    })
                    ->visible(fn (NewsletterSubscriber $record): bool => $record->status === NewsletterStatus::SUBSCRIBED),
            ])
            ->headerActions([
                Action::make('export_csv')
                    ->label('Esporta CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $subscribers = NewsletterSubscriber::where('status', NewsletterStatus::SUBSCRIBED)->get();
                        
                        $headers = [
                            'Content-Type' => 'text/csv',
                            'Content-Disposition' => 'attachment; filename="newsletter-subscribers-' . now()->format('Y-m-d') . '.csv"',
                        ];
                        
                        return response()->streamDownload(function () use ($subscribers) {
                            $file = fopen('php://output', 'w');
                            fputcsv($file, ['Email', 'Nome', 'Stato', 'Fonte', 'Data Iscrizione']);
                            
                            foreach ($subscribers as $subscriber) {
                                fputcsv($file, [
                                    $subscriber->email,
                                    $subscriber->name,
                                    $subscriber->status->getLabel(),
                                    $subscriber->source,
                                    $subscriber->subscribed_at?->format('d/m/Y H:i'),
                                ]);
                            }
                            
                            fclose($file);
                        }, 'newsletter-subscribers-' . now()->format('Y-m-d') . '.csv', $headers);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                        
                    Tables\Actions\BulkAction::make('unsubscribe_bulk')
                        ->label('Disiscrivi selezionati')
                        ->icon('heroicon-o-x-mark')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['status' => NewsletterStatus::UNSUBSCRIBED]);
                            });
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsletterSubscribers::route('/'),
            'edit' => Pages\EditNewsletterSubscriber::route('/{record}/edit'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false; // Solo iscrizioni da frontend
    }
}