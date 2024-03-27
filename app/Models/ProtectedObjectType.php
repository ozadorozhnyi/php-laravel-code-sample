<?php

namespace App\Models;

use App\Models\Localizations\ProtectedObjectTypeLocalization;
use App\Models\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProtectedObjectType extends LocalizationModel
{
    use HasFactory, BaseTrait;

    public const OBJ_FLAT = 1;
    public const OBJ_OFFICE_SPACE = 2;
    public const OBJ_PRIVATE_HOUSEHOLD = 3;
    public const OBJ_COMMERCIAL_PREMISES = 4;

    protected $localeModel = ProtectedObjectTypeLocalization::class;
    protected $locale_key = 'type_id';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'icon',
        'icon_ios'
    ];
}
