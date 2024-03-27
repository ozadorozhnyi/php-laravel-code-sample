<?php

namespace App\Models\Alarm;

trait ScopeOfType
{
    /**
     * Scope a query to only include instance of a given slug.
     *
     * @param $query
     * @param $type
     * @return mixed
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('slug', $type)->first();
    }
}
