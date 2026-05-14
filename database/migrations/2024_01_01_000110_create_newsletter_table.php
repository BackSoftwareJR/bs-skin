<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->enum('status', ['pending', 'subscribed', 'unsubscribed', 'bounced', 'complained'])->default('pending');
            $table->string('source', 50)->nullable()->comment('footer, popup, checkout, import');
            $table->string('locale', 5)->default('it');
            $table->string('external_provider', 50)->nullable();
            $table->string('external_subscriber_id')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->string('double_opt_in_token', 60)->nullable();
            $table->timestamp('double_opt_in_expires_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index(['external_provider', 'external_subscriber_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers');
    }
};