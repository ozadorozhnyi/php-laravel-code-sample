<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait RepositoryFilter
 * Реализация для возмоности простого переключения на другое хранилище, типа elasticsearch
 * @package App\Traits
 */
trait RepositoryFilter
{
    protected $builder = null;

    /**
     * @return Builder
     * @throws \Exception
     */
    public function builder()
    {
        if (is_null($this->builder)) {
            if (empty($this->model)) {
                throw new \Exception('Repository model not set');
            }

            $this->builder = $this->model::query();
        }

        return $this->builder;
    }

    /**
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function get()
    {
        return $this->builder()->get();
    }

    /**
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \Exception
     */
    public function first()
    {
        return $this->builder()->first();
    }
}
