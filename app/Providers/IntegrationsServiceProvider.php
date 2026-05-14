<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\InvoiceProvider;
use App\Contracts\NewsletterProvider;
use App\Contracts\PaymentGateway;
use App\Contracts\ShippingProvider;
use App\Integrations\Invoice\ArubaInvoiceProvider;
use App\Integrations\Invoice\FattureInCloudInvoiceProvider;
use App\Integrations\Invoice\NoneInvoiceProvider;
use App\Integrations\Newsletter\DatabaseNewsletterProvider;
use App\Integrations\Newsletter\MailchimpNewsletterProvider;
use App\Integrations\Payment\ManualPaymentGateway;
use App\Integrations\Payment\PayPalPaymentGateway;
use App\Integrations\Payment\StripePaymentGateway;
use App\Integrations\Shipping\ManualShippingProvider;
use Illuminate\Support\ServiceProvider;

class IntegrationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentGateway::class, function () {
            return match (config('skintemple.payment.default_provider', 'manual')) {
                'stripe' => new StripePaymentGateway(),
                'paypal' => new PayPalPaymentGateway(),
                default => new ManualPaymentGateway(),
            };
        });

        $this->app->bind(NewsletterProvider::class, function () {
            return match (config('skintemple.newsletter.provider', 'database')) {
                'mailchimp' => new MailchimpNewsletterProvider(),
                default => new DatabaseNewsletterProvider(),
            };
        });

        $this->app->bind(InvoiceProvider::class, function () {
            return match (config('skintemple.invoice.provider', 'none')) {
                'aruba' => new ArubaInvoiceProvider(),
                'fatture_in_cloud' => new FattureInCloudInvoiceProvider(),
                default => new NoneInvoiceProvider(),
            };
        });

        $this->app->bind(ShippingProvider::class, function () {
            return new ManualShippingProvider();
        });
    }
}
