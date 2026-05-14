<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\AuthController;
use App\Http\Controllers\Public\CartController;
use App\Http\Controllers\Public\CouponController;
use App\Http\Controllers\Public\CheckoutController;
use App\Http\Controllers\Public\NewsletterController;
use App\Http\Controllers\Public\SitemapController;

// ── Sitemap ──────────────────────────────────────────────────────────
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', function() {
    return response("User-agent: *\nAllow: /\nDisallow: /admin/\nSitemap: " . url('/sitemap.xml'), 200, ['Content-Type' => 'text/plain']);
})->name('robots');

// ── Frontend pubblico ─────────────────────────────────────────────────
Route::get('/', fn() => view('public.home'))->name('home');

// Shop / Catalogo
Route::get('/shop', fn() => view('public.shop.index'))->name('shop.index');
Route::get('/categoria/{slug}', fn($slug) => view('public.shop.index', ['categorySlug' => $slug]))->name('category.show');
Route::get('/prodotto/{slug}', fn($slug) => view('public.product.show', ['slug' => $slug]))->name('product.show');
Route::get('/marchio/{slug}', fn($slug) => view('public.shop.index', ['brandSlug' => $slug]))->name('brand.show');
Route::get('/tecnologie', fn() => view('public.shop.index', ['categorySlug' => 'tecnologie']))->name('technologies.index');
Route::get('/cerca', fn() => view('public.shop.index'))->name('search');

// Cart (Livewire gestisce la maggior parte, ma mantieni route HTTP per fallback non-JS)
Route::get('/carrello', fn() => view('public.cart.index'))->name('cart.index');
Route::post('/carrello/items', [CartController::class, 'store'])->name('cart.items.store');
Route::patch('/carrello/items/{cartItemId}', [CartController::class, 'update'])->name('cart.items.update');
Route::delete('/carrello/items/{cartItemId}', [CartController::class, 'destroy'])->name('cart.items.destroy');
Route::post('/carrello/coupon', [CouponController::class, 'store'])->name('cart.coupon.store');
Route::delete('/carrello/coupon', [CouponController::class, 'destroy'])->name('cart.coupon.destroy');

// Checkout
Route::get('/checkout', fn() => view('public.cart.checkout'))->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('throttle:5,1');

// Auth (OTP passwordless)
Route::get('/account/login', fn() => view('public.account.login'))->name('account.login');
Route::post('/account/otp/request', [AuthController::class, 'requestOtp'])->name('account.otp.request')->middleware('throttle:3,1');
Route::post('/account/otp/verify', [AuthController::class, 'verifyOtp'])->name('account.otp.verify')->middleware('throttle:5,1');
Route::post('/account/logout', [AuthController::class, 'logout'])->name('account.logout');

// Account (richiedono auth)
Route::middleware(['customer.auth'])->prefix('account')->name('account.')->group(function () {
    Route::get('/', fn() => view('public.account.dashboard'))->name('dashboard');
    Route::get('/ordini', fn() => view('public.account.orders'))->name('orders.index');
    Route::get('/ordini/{orderNumber}', fn($n) => view('public.account.order-detail', ['orderNumber' => $n]))->name('orders.show');
    Route::get('/indirizzi', fn() => view('public.account.addresses'))->name('addresses.index');
    Route::get('/wishlist', fn() => view('public.account.wishlist'))->name('wishlist.index');
    Route::get('/profilo', fn() => view('public.account.profile'))->name('profile.edit');
});

// Newsletter
Route::post('/newsletter/iscriviti', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe')->middleware('throttle:3,5');
Route::get('/newsletter/conferma/{email}/{token}', [NewsletterController::class, 'confirm'])->name('newsletter.confirm');

// Blog
Route::get('/blog', fn() => view('public.blog.index'))->name('blog.index');
Route::get('/blog/{slug}', fn($slug) => view('public.blog.show', ['slug' => $slug]))->name('blog.show');

// Pagine CMS generiche (deve essere ULTIMA per non sovrascrivere rotte specifiche)
Route::get('/{slug}', fn($slug) => view('public.pages.show', ['slug' => $slug]))
    ->name('page.show')
    ->where('slug', '^(?!admin|api|webhooks|sitemap\.xml|robots\.txt).*$');