<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Localizations\RegionLocalization;
use Mcamara\LaravelLocalization\LaravelLocalization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class Region
 * @package App\Models
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property RegionLocalization $locale
 * @property Collection|RegionLocalization[] $locales
 * @method static $this|Builder orderByLocaleName(string $sort)
 */
class Region extends LocalizationModel
{
    protected $localeModel = RegionLocalization::class;

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
        $locale = RegionLocalization::where('region_id', $this->id)->where('locale', $locale)->first();
        $this->setRelation('locale', $locale);

        return $this;
    }

    /**
     * @param Builder $builder
     * @param string $sort
     */
    public function scopeOrderByLocaleName(Builder $builder, string $sort = 'asc')
    {
        $builder->leftJoin((new RegionLocalization())->getTable() . ' as locale', function($query){
            return $query->where('locale.region_id', DB::Raw('regions.id'))
                ->where('locale.locale', (new LaravelLocalization)->getCurrentLocale());
        })
            ->orderBy('locale.name', $sort);
    }
}
