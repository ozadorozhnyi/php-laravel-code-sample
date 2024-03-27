<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsValidTimestamp implements Rule
{
    protected $timestamp;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($timestamp)
    {
        $this->timestamp = $timestamp;
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
        return ((string) (int) $this->timestamp === $this->timestamp)
            && ($this->timestamp <= PHP_INT_MAX)
            && ($this->timestamp >= ~PHP_INT_MAX);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('template.error.rule.is_valid_timestamp');
    }
}
