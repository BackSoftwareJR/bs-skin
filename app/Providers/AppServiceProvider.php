<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\OtpCode;
use App\Models\Product;
use App\Observers\CategoryObserver;
use App\Observers\OtpCodeObserver;
use App\Observers\ProductObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Observers
        \App\Models\Product::observe(\App\Observers\ProductObserver::class);
        \App\Models\Category::observe(\App\Observers\CategoryObserver::class);
        \App\Models\OtpCode::observe(\App\Observers\OtpCodeObserver::class);
        \App\Models\Block::observe(\App\Observers\BlockObserver::class);
        \App\Models\Menu::observe(\App\Observers\MenuObserver::class);
        \App\Models\Cart::observe(\App\Observers\CartObserver::class);

        // Spatie Media Library: set image driver
        \Spatie\MediaLibrary\MediaCollections\Models\Media::saving(function ($media) {
            // GD driver only
        });

        // Pagination via Blade components
        \Illuminate\Pagination\Paginator::useBootstrapFive();
        // Solo se il componente esiste:
        if (view()->exists('components.public.pagination')) {
            \Illuminate\Pagination\Paginator::defaultView('components.public.pagination');
            \Illuminate\Pagination\Paginator::defaultSimpleView('components.public.pagination');
        }

        // Macro: money format
        \Illuminate\Support\Str::macro('money', function (float $amount, string $currency = 'EUR'): string {
            return '€' . number_format($amount, 2, ',', '.');
        });

        // Events and Listeners (commented out until Listeners are created)
        // \Illuminate\Support\Facades\Event::listen(
        //     \App\Events\OrderPlaced::class,
        //     [\App\Listeners\SendOrderConfirmationEmail::class, \App\Listeners\SendAdminNewOrderNotification::class]
        // );
        // \Illuminate\Support\Facades\Event::listen(\App\Events\OrderStatusChanged::class, \App\Listeners\SendOrderStatusChangedEmail::class);
        // \Illuminate\Support\Facades\Event::listen(\App\Events\OtpRequested::class, \App\Listeners\SendOtpEmail::class);
        // \Illuminate\Support\Facades\Event::listen(\App\Events\NewsletterSubscribed::class, \App\Listeners\SendNewsletterDoubleOptIn::class);
    }
}
