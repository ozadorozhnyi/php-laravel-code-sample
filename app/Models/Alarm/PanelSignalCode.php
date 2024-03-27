<?php

namespace App\Models\Alarm;

use App\Models\LocalizationModel;
use App\Models\Localizations\Alarm\PanelSignalCodeLocalization;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PanelSignalCode extends LocalizationModel
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alarm_panel_signal_codes';

    /**
     * Localization model class.
     *
     * @var string
     */
    protected $localeModel = PanelSignalCodeLocalization::class;

    /**
     * Localization model foreign key name.
     *
     * @var string
     */
    protected $locale_key = 'signal_code_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'slug',
        'trigger_alarm'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'trigger_alarm' => 'boolean',
    ];
}
