<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhooks\StripeWebhookController;
use App\Http\Controllers\Webhooks\PayPalWebhookController;

// Webhook — CSRF già escluso per pattern 'api/*' in bootstrap/app.php
Route::post('/webhooks/stripe', StripeWebhookController::class)->name('webhooks.stripe');
Route::post('/webhooks/paypal', PayPalWebhookController::class)->name('webhooks.paypal');