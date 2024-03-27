<?php

namespace App\Rules\Payment\Card;

use Illuminate\Contracts\Validation\Rule;

class OnlyOneLinked implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return 0 === user()->paymentCard()->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('template.error.rule.payment.card.only_one_linked');
    }
}
