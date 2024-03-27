<?php

namespace App\Models;

use App\Models\Localizations\CityLocalization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\LaravelLocalization;

/**
 * Class City
 * @package App\Models
 * @property int $id
 * @property int $region_id
 * @property string $code
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property CityLocalization $locale
 * @property Collection|CityLocalization[] $locales
 * @method static $this|Builder whereLocaleName(string $name)
 * @method static $this|Builder orderByLocaleName(string $sort)
 * @method static $this|Builder orderByCategoryPeriodPlace(int $category_id, int $period_id, string $sort = 'asc')
 */
class City extends LocalizationModel
{
    protected $localeModel = CityLocalization::class;

    /**
     * @var array
     */
    protected $fillable = [
        'region_id', 'code'
    ];

    public function partners()
    {
        return $this->hasMany(Partner::class);
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function loadLocaleRelations(string $locale)
    {
        $locale = CityLocalization::where('city_id', $this->id)->where('locale', $locale)->first();
        $this->setRelation('locale', $locale);

        return $this;
    }

    /**
     * @return BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * @param Builder $builder
     * @param string $name
     */
    public function scopeWhereLocaleName(Builder $builder, string $name)
    {
        $builder->leftJoin((new CityLocalization())->getTable() . ' as locale', function($query){
            return $query->where('locale.city_id', DB::Raw('cities.id'))
                ->where('locale.locale', (new LaravelLocalization)->getCurrentLocale());
        })
            ->where('locale.name', 'like', '%' . clean_query_string($name) . '%');
    }

    /**
     * @param Builder $builder
     * @param string $name
     */
    public function scopeOrderByLocaleName(Builder $builder, string $sort = 'asc')
    {
        $builder->leftJoin((new CityLocalization())->getTable() . ' as locale', function($query){
            return $query->where('locale.city_id', DB::Raw('cities.id'))
                ->where('locale.locale', (new LaravelLocalization)->getCurrentLocale());
        })
            ->orderBy('locale.name', $sort);
    }

}
