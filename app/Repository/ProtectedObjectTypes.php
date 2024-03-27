<?php

namespace App\Repository;

use App\Traits\RepositoryFilter;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ProtectedObjectTypes
{
    use RepositoryFilter;

    /**
     * @var array
     */
    protected static array $items = [];

    /**
     * @var string
     */
    protected $model = \App\Models\ProtectedObjectType::class;

    /**
     * @return Collection|null
     * @throws \Exception
     */
    public function all($locale)
    {
        if(!isset(static::$items[$locale])) {
            static::$items[$locale] = Cache::tags('protected_object_types')->remember('protected_object_types::' . $locale,
                Carbon::now()->addHour(), function () {
                    return $this->builder()
                        ->withCurrentLocale()
                        ->get();
                }
            );
        }

        return static::$items[$locale];
    }
}
