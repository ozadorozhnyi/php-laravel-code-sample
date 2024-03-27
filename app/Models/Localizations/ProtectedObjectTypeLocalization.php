<?php

namespace App\Models\Localizations;

use App\Models\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProtectedObjectTypeLocalization
 * @package App\Models\Localizations
 * @property int $id
 * @property int $type_id
 * @property string $locale
 */
class ProtectedObjectTypeLocalization extends Model
{
    use BaseTrait;

    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string[]
     */
    protected $fillable = [
        'type_id',
        'locale'
    ];
}
