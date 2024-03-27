<?php

namespace App\Models\Shop;

use Carbon\Carbon;
use App\Models\LocalizationModel;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Localizations\Shop\ProductLocalization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends LocalizationModel implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shop_products';

    /**
     * Localization model class.
     * @var string
     */
    protected $localeModel = ProductLocalization::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'manufacturer_id',
        'sku', 'price',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    /**
     * Accessor!
     *
     * Detect is current item a new arrival.
     *
     * New arrival - it's item, that was added into the table
     * less than X days ago (inclusive). X-value is configurable.
     *
     * @return bool
     */
    public function getIsANewArrivalAttribute():bool
    {
        $expireAfter = (int)config('shop.new_arrival_expire_days');
        $daysDiff = (int)$this->created_at->diffInDays(Carbon::now());

        return $daysDiff <= $expireAfter;
    }

    /**
     * Accessor!
     *
     * Get product price with two .00 decimals everytime.
     *
     * @param  string  $value
     * @return string
     */
    public function getPriceAttribute($value)
    {
        return sprintf("%01.2f", $value);
    }

    /**
     * Product Photo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photo()
    {
        // set hasOne relation with an existing Media model which extends Spatie/MediaLibrary
        $hasOne = $this->hasOne('App\Models\Media', 'model_id');

        // just add where condition to extract rows for THIS MODEL TYPE ONLY
        $hasOne->getQuery()->where('model_type', self::class);

        return $hasOne;
    }

    /**
     * Add media conversions for the photo, associated with a Product.
     *
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10);
    }

    /**
     * Category
     * @return BelongsTo
     */
    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Manufacturer
     * @return BelongsTo
     */
    public function manufacturer():BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    /**
     * Get all products with the same category.
     *
     * Used in the seeder class, to build suggested products,
     * based on the same category_id.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeTheSameCategoryProducts(Builder $query)
    {
        return $query->whereHas('category', function($query){
            $query->where('category_id', $this->category->id);
        })->whereNotIn('id', [$this->id]);
    }

    /**
     * Get all related/suggested products.
     *
     * @return BelongsToMany
     */
    public function scopeRelatedProducts():BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'shop_product_relations',
            'product_id', 'related_product_id'
        )->withTimestamps();
    }

    public function scopeWithCategory(Builder $query)
    {
        return $query->with([
            'category' => fn($builder) => $builder->withCurrentLocale()
        ]);
    }

    /**
     * Related products.
     *
     * @param Builder $query
     * @param int $max_qty
     * @return Builder
     */
    public function scopeWithRelatedProducts(Builder $query, int $max_qty = 6)
    {
        return $query->with([
            'relatedProducts' => fn($builder) => $builder->withCurrentLocale()
                ->with([
                    'manufacturer', 'photo',
                    'category' => fn($query) => $query->withCurrentLocale(),
                ])->limit($max_qty),
        ]);
    }
}
