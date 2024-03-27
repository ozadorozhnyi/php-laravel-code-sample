<?php

namespace App\Models\Localizations;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PartnerLocalization
 * @package App\Models\Localizations
 * @property int $id
 * @property int $partner_id
 * @property string $name
 * @property string $locale
 */
class PartnerLocalization extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'partner_id', 'name', 'locale',
    ];
}
