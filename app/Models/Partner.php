<?php

namespace App\Models;

use App\Models\Shop\ConsultationRequest;
use Carbon\Carbon;
use App\Models\Localizations\PartnerLocalization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Partner
 * @package App\Models
 * @property int $id
 * @property string $phone
 * @property boolean $is_basic
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property PartnerLocation $locale
 * @property Collection|PartnerLocalization[] $locales
 */
class Partner extends LocalizationModel
{
    use HasFactory;

    protected $localeModel = PartnerLocalization::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone',
        'is_basic'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_basic' => 'boolean',
    ];

    public function loadLocaleRelations(string $locale)
    {
        $locale = PartnerLocalization::where('partner_id', $this->id)->where('locale', $locale)->first();
        $this->setRelation('locale', $locale);

        return $this;
    }

    public function locations()
    {
        return $this->hasMany(PartnerLocation::class);
    }

    public function consultationRequests():HasMany
    {
        return $this->hasMany(
            ConsultationRequest::class,
            'partner_id',
            'id'
        );
    }
}
