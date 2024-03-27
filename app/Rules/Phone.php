<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class Phone
 * @package App\Rules
 */
class Phone implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return is_string($value) && preg_match('/^\+[0-9]{3,20}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('template.error.rule.phone');
    }
}
