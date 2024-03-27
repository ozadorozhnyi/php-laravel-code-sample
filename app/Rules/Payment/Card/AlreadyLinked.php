<?php

namespace App\Rules\Payment\Card;

use Illuminate\Contracts\Validation\Rule;

class AlreadyLinked implements Rule
{
    protected $token;

    /**
     * Create a new rule instance.
     *
     * @return void
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
        $exists = user()->paymentCard()
            ->where('token', $this->token)
            ->exists();

        return ! $exists;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('template.error.rule.payment.card.already_exists');
    }
}
