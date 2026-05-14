<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Translatable\HasTranslations;

class SeoMeta extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['meta_title', 'meta_description', 'og_title', 'og_description'];

    protected $fillable = [
        'seoable_type', 'seoable_id', 'meta_title', 'meta_description',
        'og_title', 'og_description', 'og_image_path', 'canonical_url',
        'robots', 'schema_markup_json', 'twitter_card'
    ];

    protected function casts(): array
    {
        return [
            'schema_markup_json' => 'array',
            'meta_title' => 'array',
            'meta_description' => 'array',
            'og_title' => 'array',
            'og_description' => 'array',
        ];
    }

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}