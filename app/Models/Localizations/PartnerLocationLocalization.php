<?php

namespace App\Models\Localizations;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PartnerLocationLocalization
 * @package App\Models\Localizations
 * @property int $id
 * @property int $partner_location_id
 * @property string $street
 * @property string $locale
 */
class PartnerLocationLocalization extends Model
{
    protected $table = 'partner_locations_localizations';

    public $timestamps = false;

    protected $fillable = [
        'partner_location_id', 'street', 'locale',
    ];
}
