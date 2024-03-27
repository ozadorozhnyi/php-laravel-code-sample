<?php

namespace App\Models;

use App\Models\Localizations\UserTypeLocalization;
use App\Models\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class UserType
 * @package App\Models
 * @property int $id
 */
class UserType extends LocalizationModel
{
    use HasFactory,
        BaseTrait;

    protected $localeModel = UserTypeLocalization::class;

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

    ];
}
