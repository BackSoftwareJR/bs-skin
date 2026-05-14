<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class KpiStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        // Calcoli per oggi
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())
            ->where('payment_status', 'captured')
            ->sum('total');
            
        // Calcoli per questa settimana
        $weekStart = Carbon::now()->startOfWeek();
        $weekOrders = Order::where('created_at', '>=', $weekStart)->count();
        $weekRevenue = Order::where('created_at', '>=', $weekStart)
            ->where('payment_status', 'captured')
            ->sum('total');
            
        // Calcoli per il mese
        $monthStart = Carbon::now()->startOfMonth();
        $monthOrders = Order::where('created_at', '>=', $monthStart)->count();
        $monthRevenue = Order::where('created_at', '>=', $monthStart)
            ->where('payment_status', 'captured')
            ->sum('total');

        return [
            Stat::make('Ordini Oggi', $todayOrders)
                ->description('Ordini ricevuti oggi')
                ->descriptionIcon('heroicon-o-shopping-bag')
                ->color('success'),
                
            Stat::make('Fatturato Oggi', '€ ' . number_format($todayRevenue, 2))
                ->description('Pagamenti completati oggi')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),
                
            Stat::make('Ordini Settimana', $weekOrders)
                ->description('Da lunedì')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('info'),
                
            Stat::make('Fatturato Settimana', '€ ' . number_format($weekRevenue, 2))
                ->description('Da lunedì')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('info'),
        ];
    }
}