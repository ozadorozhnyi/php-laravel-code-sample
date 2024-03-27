<?php

namespace App\Rules;

use App\Models\UserPhoneVerify;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class IsPhoneVerify
 * @package App\Rules
 */
class IsPhoneVerified implements Rule
{
    /**
     * @var
     */
    protected $token;

    /**
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        return UserPhoneVerify::where('phone', $value)
            ->where('token', $this->token)
            ->where('verified', true)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('template.error.rule.is_phone_verified');
    }
}
