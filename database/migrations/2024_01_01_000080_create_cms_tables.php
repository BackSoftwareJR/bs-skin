<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('title')->comment('Translatable: {"it":"..."}');
            $table->string('template', 50)->default('default');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('seo_meta_id')->nullable();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('location', 100)->nullable()->comment('Es. home_hero, footer_promo');
            $table->string('type', 50)->comment('hero, text, features, product_grid, cta, contact_form...');
            $table->json('content_json');
            $table->json('settings_json')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index(['location', 'is_active', 'sort_order']);
            $table->index(['page_id', 'sort_order']);
        });

        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->string('location', 50);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete();
            $table->json('label')->comment('Translatable: {"it":"..."}');
            $table->enum('type', ['page', 'category', 'brand', 'product', 'blog', 'custom'])->default('custom');
            $table->string('target_type', 100)->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->string('url', 500)->nullable();
            $table->string('icon', 50)->nullable();
            $table->string('badge_label', 20)->nullable();
            $table->boolean('opens_in_new_tab')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['menu_id', 'parent_id', 'sort_order']);
        });

        Schema::create('seo_meta', function (Blueprint $table) {
            $table->id();
            $table->string('seoable_type')->nullable();
            $table->unsignedBigInteger('seoable_id')->nullable();
            $table->json('meta_title')->nullable()->comment('Translatable');
            $table->json('meta_description')->nullable()->comment('Translatable');
            $table->json('og_title')->nullable()->comment('Translatable');
            $table->json('og_description')->nullable()->comment('Translatable');
            $table->string('og_image_path', 500)->nullable();
            $table->string('canonical_url', 500)->nullable();
            $table->string('robots', 100)->default('index,follow');
            $table->json('schema_markup_json')->nullable();
            $table->string('twitter_card', 50)->default('summary_large_image');
            $table->timestamps();
            
            $table->index(['seoable_type', 'seoable_id']);
        });

        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('source_url', 500)->unique();
            $table->string('destination_url', 500);
            $table->smallInteger('status_code')->default(301);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('hits')->default(0);
            $table->timestamps();
        });

        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->text('subject_template');
            $table->longText('body_html_template');
            $table->longText('body_text_template')->nullable();
            $table->json('available_variables_json')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('redirects');
        Schema::dropIfExists('seo_meta');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('blocks');
        Schema::dropIfExists('pages');
    }
};