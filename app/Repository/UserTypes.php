<?php

namespace App\Repository;

use App\Traits\RepositoryFilter;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class UserTypes
{
    use RepositoryFilter;

    /**
     * @var array
     */
    protected static $items = [];

    /**
     * @var string
     */
    protected $model = \App\Models\UserType::class;

    /**
     * @return Collection|null
     * @throws \Exception
     */
    public function all($locale)
    {
        if(!isset(static::$items[$locale])) {
            static::$items[$locale] = Cache::tags('user_types')->remember('user_types::' . $locale,
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
