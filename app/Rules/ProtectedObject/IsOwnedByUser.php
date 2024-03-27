<?php

namespace App\Rules\ProtectedObject;

use App\Exceptions\FailedValidationException;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class IsOwnedByUser implements Rule
{
    /**
     * @var int
     */
    protected int $protected_object_id;

    /**
     * Is owned by user.
     *
     * @return void
     */
    public function __construct(int $protected_object_id)
    {
        $this->protected_object_id = $protected_object_id;
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
        return user()->protectedObjects()->pluck('id')->contains(
            $this->protected_object_id
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('template.error.rule.object_is_not_owned_by_current_user');
    }

    public function failedValidation(Validator $validator)
    {
        throw new FailedValidationException($validator);
    }
}
