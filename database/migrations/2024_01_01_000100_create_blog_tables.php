<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->json('name')->comment('Translatable: {"it":"..."}');
            $table->string('slug')->unique();
            $table->json('description')->nullable()->comment('Translatable');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('blog_tags', function (Blueprint $table) {
            $table->id();
            $table->json('name')->comment('Translatable: {"it":"..."}');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_category_id')->nullable()->constrained()->nullOnDelete();
            $table->json('title')->comment('Translatable: {"it":"..."}');
            $table->string('slug')->unique();
            $table->json('excerpt')->nullable()->comment('Translatable');
            $table->json('body_html')->comment('Translatable');
            $table->string('featured_image_path', 500)->nullable();
            $table->foreignId('author_user_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('reading_time_minutes')->nullable();
            $table->foreignId('seo_meta_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['is_published', 'published_at']);
            $table->index('blog_category_id');
        });

        Schema::create('blog_post_blog_tag', function (Blueprint $table) {
            $table->foreignId('blog_post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('blog_tag_id')->constrained()->cascadeOnDelete();
            
            $table->primary(['blog_post_id', 'blog_tag_id'], 'bpbt_primary');
            $table->index('blog_tag_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_post_blog_tag');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('blog_tags');
        Schema::dropIfExists('blog_categories');
    }
};