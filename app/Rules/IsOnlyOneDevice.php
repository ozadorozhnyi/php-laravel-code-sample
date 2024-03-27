<?php

namespace App\Rules;

use App\Models\ProtectedObject;
use Illuminate\Contracts\Validation\Rule;

class IsOnlyOneDevice implements Rule
{
    /**
     * @var int
     */
    protected int $protected_object_id;

    /**
     * IsOnlyOneDevice constructor.
     *
     * @param int $protected_object_id
     */
    public function __construct(int $protected_object_id)
    {
        $this->protected_object_id = $protected_object_id;
    }

    /**
     * Ensure, that only one device steel assigned
     * to the protected object at one time.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $protected_object = ProtectedObject::find(
            $this->protected_object_id
        );

        if (!$protected_object) {
            return false;
        }

        return 0 === $protected_object->device()->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('template.error.rule.only_one_attached_device_allowed');
    }
}
