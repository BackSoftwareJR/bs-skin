<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class DashboardPage extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static ?string $navigationLabel = 'Dashboard';
    
    protected static ?string $title = 'Dashboard SkinTemple';

    public function getColumns(): int | string | array
    {
        return 2;
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\KpiStatsWidget::class,
            \App\Filament\Widgets\LowStockWidget::class,
            \App\Filament\Widgets\RecentOrdersWidget::class,
            \App\Filament\Widgets\NewsletterStatsWidget::class,
            \App\Filament\Widgets\RecentActivityWidget::class,
        ];
    }
}