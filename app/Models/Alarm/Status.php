<?php

namespace App\Models\Alarm;

use App\Models\LocalizationModel;
use App\Models\Localizations\Alarm\StatusLocalization;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Status
 * @package App\Models\Alarm
 */
class Status extends LocalizationModel
{
    use HasFactory;
    use ScopeOfType;

    public const ACTIVE = 'active';
    public const CANCELED_BY_CUSTOMER = 'canceled_by_customer';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alarm_statuses';

    /**
     * Localization model class.
     *
     * @var string
     */
    protected $localeModel = StatusLocalization::class;

    /**
     * Localization model foreign key name.
     *
     * @var string
     */
    protected $locale_key = 'alarm_status_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'slug',
    ];
}
