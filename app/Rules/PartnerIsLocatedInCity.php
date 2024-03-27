<?php

namespace App\Rules;

use App\Models\Partner;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class PartnerIsLocatedInCity implements Rule
{
    protected int $partner_id;
    protected int $partner_city_id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $partner_id, int $partner_city_id)
    {
        $this->partner_id = $partner_id;
        $this->partner_city_id = $partner_city_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $partner = Partner::find($this->partner_id);

        if (!$partner)
            return false;

        $exists = $partner->whereHas('locations', function (Builder $query) {
            $query->where('city_id', $this->partner_city_id);
        })->exists();

        return $exists;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('template.error.rule.partner_is_not_located_in_city');
    }
}
