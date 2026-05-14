<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 20)->unique();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_email');
            $table->string('customer_name');
            $table->json('shipping_address_json');
            $table->json('billing_address_json');
            $table->string('currency', 3)->default('EUR');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount_total', 12, 2)->default(0.00);
            $table->decimal('tax_total', 12, 2)->default(0.00);
            $table->decimal('shipping_total', 12, 2)->default(0.00);
            $table->decimal('total', 12, 2);
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded', 'partially_refunded'])->default('pending');
            $table->enum('payment_status', ['pending', 'authorized', 'captured', 'failed', 'refunded', 'partially_refunded'])->default('pending');
            $table->string('payment_provider', 50)->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_external_id')->nullable();
            $table->json('payment_metadata')->nullable();
            $table->string('shipping_provider', 50)->nullable();
            $table->string('shipping_method', 100)->nullable();
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->text('notes_customer')->nullable();
            $table->text('notes_admin')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            // Fatturazione elettronica
            $table->string('invoice_number', 50)->nullable();
            $table->string('invoice_external_id')->nullable();
            $table->string('invoice_provider', 50)->nullable();
            $table->string('invoice_pdf_path', 500)->nullable();
            $table->string('invoice_xml_path', 500)->nullable();
            $table->enum('invoice_status', ['none', 'pending', 'sent', 'accepted', 'rejected'])->default('none');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'created_at']);
            $table->index(['customer_id', 'status']);
            $table->index('payment_status');
            $table->index('invoice_status');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sku', 64);
            $table->string('name');
            $table->string('variant_name')->nullable();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('tax_rate', 5, 2);
            $table->decimal('tax_amount', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('total', 12, 2);
            $table->json('product_snapshot_json')->nullable()->comment('Snapshot completo del prodotto al momento ordine');
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('product_id');
        });

        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('from_status', 50)->nullable();
            $table->string('to_status', 50);
            $table->text('reason')->nullable();
            $table->foreignId('performed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at');
            
            $table->index('order_id');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 50);
            $table->string('method', 50)->nullable();
            $table->string('external_id')->nullable();
            $table->string('status', 50)->default('pending');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('EUR');
            $table->json('metadata_json')->nullable();
            $table->json('webhook_payload_json')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            $table->index('order_id');
            $table->index(['provider', 'external_id']);
        });

        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->text('reason');
            $table->string('external_id')->nullable();
            $table->string('status', 50)->default('pending');
            $table->foreignId('processed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('payment_id');
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->string('provider', 50);
            $table->boolean('is_active')->default(false);
            $table->integer('sort_order')->default(0);
            $table->json('config_json')->nullable();
            $table->text('instructions')->nullable();
            $table->string('icon_path', 500)->nullable();
            $table->timestamps();
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('carrier', 100);
            $table->string('tracking_number', 100)->nullable();
            $table->string('tracking_url', 500)->nullable();
            $table->string('status', 50)->default('pending');
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->unsignedInteger('weight_grams')->nullable();
            $table->string('label_pdf_path', 500)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('tracking_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_status_history');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};