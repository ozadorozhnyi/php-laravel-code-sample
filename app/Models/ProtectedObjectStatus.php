<?php

namespace App\Models;

use App\Models\Localizations\ProtectedObjectStatusLocalization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\LocalizationModel;

class ProtectedObjectStatus extends LocalizationModel
{
    use HasFactory;

    public const SUCCESSFULLY_REGISTERED = 'successfully_registered';
    public const EQUIPMENT_SUCCESSFULLY_ADDED = 'equipment_successfully_added';
    public const WAITING_FOR_THE_EQUIPMENT = 'waiting_for_the_equipment';
    public const SUCCESSFULLY_CONNECTED_TO_THE_SECURITY_SERVICES = 'successfully_connected_to_the_security_services';
    public const DISCONNECTED_FROM_THE_SECURITY_SERVICES = 'disconnected_from_the_security_services';
    public const TEMPORARY_DISCONNECTED_FROM_THE_SECURITY_SERVICES = 'temporary_disconnected_from_the_security_services';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'protected_object_statuses';

    /**
     * Localization model class.
     * @var string
     */
    protected $localeModel = ProtectedObjectStatusLocalization::class;

    /**
     * Localization model foreign key name.
     *
     * @var string
     */
    protected $locale_key = 'status_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'code',
        'color',
    ];
}
