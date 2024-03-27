<?php

namespace App\Repository;

use App\Traits\RepositoryFilter;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class OwnerObject
{
    use RepositoryFilter;

    /**
     * @var string
     */
    protected $model = \App\Models\OwnerObject::class;

    /**
     * @return Collection|null
     * @throws \Exception
     */
    public function all($created_user_id)
    {
        return $this->builder()
            ->where('created_user_id', $created_user_id)
            ->get();
    }
}
