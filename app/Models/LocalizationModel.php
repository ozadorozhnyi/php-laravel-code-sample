<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
class LocalizationModel extends Model
{
    protected $localeModel = Model::class;

    protected $locale_key = null;

    /**
     * @return HasMany
     */
    public function locales()
    {
        return $this->hasMany($this->localeModel, $this->locale_key);
    }

    /**
     * @return HasOne
     */
    public function locale()
    {
        return $this->hasOne($this->localeModel, $this->locale_key);
    }

    /**
     * @param array $data
     * @return Collection|Model[]
     */
    public function createLocales(array $data)
    {
        $models = new Collection();

        foreach($data as $i) {
            $locale = $this->locales()->create($i);
            $models->push($locale);
        }

        return $models;
    }

    /**
     * @param array $data
     * @return Collection|Model[]
     */
    public function createOrUpdateLocales(array $data)
    {
        $models = new Collection();

        foreach($data as $i) {
            $locale = $this->locales()->updateOrCreate(['locale' => $i['locale']], $i);
            $models->push($locale);
        }

        return $models;
    }

    /**
     * @param Builder $builder
     * @param string $locale
     * @return Builder
     */
    public function scopeWithLocale(Builder $builder, string $locale)
    {
        return $builder->with(['locale' => fn(HasOne $builder) => $builder->where('locale', $locale)]);
    }

    /**
     * @param Builder $builder
     */
    public function scopeWithCurrentLocale(Builder $builder)
    {
        $builder->withLocale(LaravelLocalization::getCurrentLocale());
    }
}
