<?php

namespace App\Models\Localizations;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CityLocalization
 * @package App\Models\Localizations
 * @property int $id
 * @property int $city_id
 * @property string $name
 * @property string $locale
 */
class CityLocalization extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'city_id', 'name', 'locale',
    ];
}
