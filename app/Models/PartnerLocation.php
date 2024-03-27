<?php

namespace App\Models;

use App\Models\Localizations\PartnerLocationLocalization;
use App\Models\Shop\ConsultationRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartnerLocation extends LocalizationModel
{
    use HasFactory;

    protected $localeModel = PartnerLocationLocalization::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'partner_id', 'city_id'
    ];

    public function loadLocaleRelations(string $locale)
    {
        $locale = PartnerLocationLocalization::where('partner_locations_id', $this->id)->where('locale', $locale)->first();
        $this->setRelation('locale', $locale);

        return $this;
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function scopeWithLocations($query)
    {
        return $query->with([
            'city' => fn($builder) => $builder->withCurrentLocale(),
            'partner' => fn($builder) => $builder->withCurrentLocale(),
        ]);
    }
}
