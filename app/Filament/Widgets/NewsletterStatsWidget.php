<?php

namespace App\Filament\Widgets;

use App\Models\NewsletterSubscriber;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class NewsletterStatsWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 1;

    public static function canView(): bool
    {
        return Schema::hasTable('newsletter_subscribers');
    }

    protected function getStats(): array
    {
        $totalSubscribers = NewsletterSubscriber::where('status', 'subscribed')->count();
        
        $weeklySubscribers = NewsletterSubscriber::where('status', 'subscribed')
            ->where('subscribed_at', '>=', Carbon::now()->subDays(7))
            ->count();
            
        $monthlySubscribers = NewsletterSubscriber::where('status', 'subscribed')
            ->where('subscribed_at', '>=', Carbon::now()->subMonth())
            ->count();

        return [
            Stat::make('Iscritti Newsletter', $totalSubscribers)
                ->description("$weeklySubscribers negli ultimi 7 giorni")
                ->descriptionIcon('heroicon-o-envelope')
                ->color('success'),
                
            Stat::make('Iscritti Mese', $monthlySubscribers)
                ->description('Nuove iscrizioni questo mese')
                ->descriptionIcon('heroicon-o-user-plus')
                ->color('info'),
        ];
    }
}