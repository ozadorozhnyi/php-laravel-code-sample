<?php

namespace App\Rules;

use App\Models\UserPhoneVerify;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class PhoneVerify
 * @package App\Rules
 */
class PhoneVerify implements Rule
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
        return UserPhoneVerify::where('code', $value)
            ->where('token', $this->token)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('template.error.rule.phone_verify');
    }
}
