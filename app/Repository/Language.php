<?php

namespace App\Repository;

use App\Traits\RepositoryFilter;
use Illuminate\Support\Collection;

class Language
{
    use RepositoryFilter;

    /**
     * @var Collection|null
     */
    protected static ?Collection $languages = null;

    /**
     * @var string
     */
    protected $model = \App\Models\Language::class;

    /**
     * @return Collection|null
     * @throws \Exception
     */
    public function all()
    {
        if(!static::$languages) {
            static::$languages = $this->get();
        }

        return static::$languages;
    }

    /**
     * @param $code
     * @return \App\Models\Language
     * @throws \Exception
     */
    public function byCode($code)
    {
        return $this->all()->where('code', $code)->first();
    }

}
