<?php

namespace App\Models\Alarm;

use App\Models\LocalizationModel;
use App\Models\Localizations\Alarm\TypeLocalization;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Type extends LocalizationModel
{
    use HasFactory;
    use ScopeOfType;

    public const AUTOMATIC = 'automatic';
    public const MANUAL = 'manual';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alarm_types';

    /**
     * Localization model class.
     *
     * @var string
     */
    protected $localeModel = TypeLocalization::class;

    /**
     * Localization model foreign key name.
     *
     * @var string
     */
    protected $locale_key = 'alarm_type_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
    ];
}
