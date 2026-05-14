<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('surname');
            $table->string('phone', 30)->nullable();
            $table->string('locale', 5)->default('it');
            $table->boolean('is_active')->default(true);
            $table->boolean('marketing_consent')->default(false);
            $table->timestamp('marketing_consent_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->unsignedInteger('total_orders')->default(0);
            $table->decimal('total_spent', 12, 2)->default(0.00);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('deleted_at');
        });

        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['shipping', 'billing', 'both'])->default('both');
            $table->boolean('is_default')->default(false);
            $table->string('full_name');
            $table->string('company')->nullable();
            $table->string('vat_number', 20)->nullable();
            $table->string('sdi_code', 7)->nullable();
            $table->string('pec')->nullable();
            $table->string('street');
            $table->string('civic', 20);
            $table->string('postal_code', 10);
            $table->string('city');
            $table->string('province', 2);
            $table->string('country', 2)->default('IT');
            $table->string('phone', 30)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('customer_id');
        });

        Schema::create('terms_acceptances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('document_version', 20);
            $table->enum('document_type', ['terms', 'privacy', 'cookie', 'marketing']);
            $table->timestamp('accepted_at');
            $table->string('ip_address', 45);
            $table->string('user_agent', 500);
            
            $table->index('customer_id');
            $table->index(['document_type', 'document_version']);
        });

        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('code_hash');
            $table->unsignedInteger('attempts')->default(0);
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->string('ip_address', 45);
            $table->string('user_agent', 500);
            $table->timestamp('created_at');
            
            $table->index(['email', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otp_codes');
        Schema::dropIfExists('terms_acceptances');
        Schema::dropIfExists('customer_addresses');
        Schema::dropIfExists('customers');
    }
};