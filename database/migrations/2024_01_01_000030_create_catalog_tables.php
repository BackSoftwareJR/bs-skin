<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->json('name')->comment('Translatable: {"it":"..."}');
            $table->string('slug')->unique();
            $table->json('description')->nullable()->comment('Translatable');
            $table->string('logo_path', 500)->nullable();
            $table->string('website_url', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->foreignId('seo_meta_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('is_active');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->json('name')->comment('Translatable: {"it":"..."}');
            $table->string('slug')->unique();
            $table->json('description')->nullable()->comment('Translatable');
            $table->enum('type', ['macroarea', 'microarea'])->default('microarea');
            $table->string('cover_image_path', 500)->nullable();
            $table->string('icon_path', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->foreignId('seo_meta_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('parent_id');
            $table->index(['type', 'is_active']);
        });

        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->json('name')->comment('Translatable: {"it":"..."}');
            $table->enum('type', ['select', 'multiselect', 'text', 'number', 'boolean', 'color', 'range'])->default('select');
            $table->boolean('is_filterable')->default(true);
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained()->cascadeOnDelete();
            $table->json('value')->comment('Translatable: {"it":"..."}');
            $table->string('slug');
            $table->integer('sort_order')->default(0);
            $table->string('color_hex', 7)->nullable();
            $table->timestamps();
            
            $table->index('attribute_id');
            $table->index('slug');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 64)->unique();
            $table->json('name')->comment('Translatable: {"it":"..."}');
            $table->string('slug')->unique();
            $table->json('short_description')->nullable()->comment('Translatable');
            $table->json('description')->nullable()->comment('Translatable');
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('product_type', ['cosmetic', 'device', 'accessory'])->default('cosmetic');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            
            // Prezzi
            $table->decimal('price', 12, 2);
            $table->decimal('compare_at_price', 12, 2)->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->string('currency', 3)->default('EUR');
            $table->decimal('tax_rate', 5, 2)->default(22.00);
            
            // Fisico / spedizione
            $table->unsignedInteger('weight_grams')->nullable();
            $table->json('dimensions_json')->nullable()->comment('{"length_cm":0,"width_cm":0,"height_cm":0}');
            $table->boolean('requires_shipping')->default(true);
            
            // Noleggio (solo device)
            $table->boolean('is_rentable')->default(false);
            $table->decimal('rental_daily_price', 12, 2)->nullable();
            $table->decimal('rental_monthly_price', 12, 2)->nullable();
            
            // Cosmetici
            $table->text('ingredients_text')->nullable();
            $table->text('inci_text')->nullable();
            $table->text('usage_instructions')->nullable();
            
            // Device
            $table->json('technical_specs_json')->nullable()->comment('{"power":"...","frequency":"..."}');
            $table->json('certifications_json')->nullable()->comment('["CE","ISO 13485"]');
            $table->unsignedInteger('warranty_months')->nullable();
            $table->string('video_demo_url', 500)->nullable();
            $table->string('manual_pdf_path', 500)->nullable();
            
            // Badge e flags
            $table->string('badge_label', 50)->nullable();
            $table->string('badge_color', 20)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_bestseller')->default(false);
            
            // Contatori
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('sales_count')->default(0);
            
            $table->timestamp('published_at')->nullable();
            $table->foreignId('seo_meta_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Colonne generate per fulltext search su JSON tradotto - MariaDB support
            if (DB::getDriverName() === 'mysql' || DB::getDriverName() === 'mariadb') {
                $table->string('name_search')->storedAs("JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.it'))")->nullable();
                $table->text('description_search')->storedAs("JSON_UNQUOTE(JSON_EXTRACT(`description`, '$.it'))")->nullable();
            }
            
            $table->index('brand_id');
            $table->index('product_type');
            $table->index(['status', 'published_at']);
            $table->index('is_featured');
            $table->index('is_new');
            
            // FULLTEXT indexes - solo su MariaDB/MySQL
            if (DB::getDriverName() === 'mysql' || DB::getDriverName() === 'mariadb') {
                $table->fullText(['sku', 'slug'], 'products_sku_slug_fulltext');
                $table->fullText(['name_search', 'description_search'], 'products_name_desc_fulltext');
            }
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('sku', 64)->unique();
            $table->string('name');
            $table->decimal('price_override', 12, 2)->nullable();
            $table->unsignedInteger('weight_grams_override')->nullable();
            $table->string('barcode', 100)->nullable();
            $table->string('image_path', 500)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('product_id');
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            
            $table->primary(['category_id', 'product_id']);
            $table->index('product_id');
        });

        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attribute_value_id')->constrained()->cascadeOnDelete();
            
            $table->primary(['product_id', 'attribute_value_id'], 'pav_primary');
            $table->index('attribute_value_id');
        });

        Schema::create('variant_attribute_values', function (Blueprint $table) {
            $table->foreignId('variant_id')->references('id')->on('product_variants')->cascadeOnDelete();
            $table->foreignId('attribute_value_id')->constrained()->cascadeOnDelete();
            
            $table->primary(['variant_id', 'attribute_value_id'], 'vav_primary');
            $table->index('attribute_value_id');
        });

        Schema::create('product_tags', function (Blueprint $table) {
            $table->id();
            $table->json('name')->comment('Translatable: {"it":"..."}');
            $table->string('slug')->unique();
            $table->string('color', 20)->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_product_tag', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_tag_id')->constrained()->cascadeOnDelete();
            
            $table->primary(['product_id', 'product_tag_id'], 'ppt_primary');
            $table->index('product_tag_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_product_tag');
        Schema::dropIfExists('product_tags');
        Schema::dropIfExists('variant_attribute_values');
        Schema::dropIfExists('product_attribute_values');
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('attribute_values');
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brands');
    }
};