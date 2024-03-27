<?php

namespace App\Models\Localizations;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RegionLocalization
 * @package App\Models\Localizations
 * @property int $id
 * @property int $region_id
 * @property string $name
 * @property string $locale
 */
class RegionLocalization extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'region_id', 'name', 'locale',
    ];
}
