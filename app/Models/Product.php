<?php

namespace App\Models;

use App\Enums\ProductStatus;
use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, HasTranslations, HasSlug, InteractsWithMedia, LogsActivity, Searchable;

    public array $translatable = ['name', 'short_description', 'description'];

    protected $fillable = [
        'sku',
        'name',
        'slug',
        'short_description',
        'description',
        'brand_id',
        'product_type',
        'status',
        'price',
        'compare_at_price',
        'cost',
        'currency',
        'tax_rate',
        'weight_grams',
        'dimensions_json',
        'requires_shipping',
        'is_rentable',
        'rental_daily_price',
        'rental_monthly_price',
        'ingredients_text',
        'inci_text',
        'usage_instructions',
        'technical_specs_json',
        'certifications_json',
        'warranty_months',
        'video_demo_url',
        'manual_pdf_path',
        'badge_label',
        'badge_color',
        'is_featured',
        'is_new',
        'is_bestseller',
        'view_count',
        'sales_count',
        'published_at',
        'seo_meta_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'cost' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'rental_daily_price' => 'decimal:2',
            'rental_monthly_price' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_bestseller' => 'boolean',
            'is_rentable' => 'boolean',
            'requires_shipping' => 'boolean',
            'dimensions_json' => 'array',
            'technical_specs_json' => 'array',
            'certifications_json' => 'array',
            'published_at' => 'datetime',
            'product_type' => ProductType::class,
            'status' => ProductStatus::class,
            'name' => 'array',
            'short_description' => 'array',
            'description' => 'array',
        ];
    }

    // Spatie Sluggable
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    // Spatie Media Library
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery');
        $this->addMediaCollection('manual_pdf')->singleFile();
        $this->addMediaCollection('video')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10);

        $this->addMediaConversion('large')
            ->width(1200)
            ->height(1200)
            ->quality(85);
    }

    // Laravel Scout
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->getTranslation('name', 'it'),
            'description' => $this->getTranslation('description', 'it'),
            'brand' => $this->brand?->getTranslation('name', 'it'),
            'type' => $this->product_type?->label(),
        ];
    }

    // Spatie Activity Log
    protected static $logAttributes = ['name', 'status', 'is_featured'];
    protected static $logName = 'products';
    protected static $logOnlyDirty = true;

    // Boot method
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->status === ProductStatus::PUBLISHED && !$model->published_at) {
                $model->published_at = now();
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('status')) {
                if ($model->status === ProductStatus::PUBLISHED && !$model->published_at) {
                    $model->published_at = now();
                } elseif ($model->status !== ProductStatus::PUBLISHED) {
                    $model->published_at = null;
                }
            }
        });
    }

    // Relations
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product')
            ->withPivot(['sort_order', 'is_primary']);
    }

    public function primaryCategory(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product')
            ->wherePivot('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_values');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ProductTag::class, 'product_product_tag');
    }

    public function inventory(): HasManyThrough
    {
        return $this->hasManyThrough(Inventory::class, ProductVariant::class);
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', ProductStatus::PUBLISHED)
            ->whereNotNull('published_at');
    }

    public function scopeActive($query)
    {
        return $this->scopePublished($query);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeNew($query)
    {
        return $query->where('is_new', true);
    }

    public function scopeBestseller($query)
    {
        return $query->where('is_bestseller', true);
    }

    public function scopeOfType($query, ProductType $type)
    {
        return $query->where('product_type', $type);
    }

    public function scopeByBrand($query, int $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    public function scopeByCategory($query, string $categorySlug)
    {
        return $query->whereHas('categories', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    public function scopeInStock($query)
    {
        return $query->whereHas('variants.inventory', function ($q) {
            $q->where('quantity', '>', 0);
        });
    }

    // Accessors
    public function getEffectivePriceAttribute(): float
    {
        return (float) $this->price;
    }

    public function getDiscountPercentageAttribute(): ?float
    {
        if (!$this->compare_at_price || $this->compare_at_price <= $this->price) {
            return null;
        }

        return round((($this->compare_at_price - $this->price) / $this->compare_at_price) * 100);
    }
}