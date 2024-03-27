<?php

namespace App\Rules;

use App\Models\ProtectedObject;
use Illuminate\Contracts\Validation\Rule;

class DeviceIsAssigned implements Rule
{
    /**
     * @var int
     */
    protected int $protected_object_id;

    /**
     * DeviceIsAssigned constructor.
     *
     * @param int $protected_object_id
     */
    public function __construct(int $protected_object_id)
    {
        $this->protected_object_id = $protected_object_id;
    }

    /**
     * Ensure, that passed hub identifier is not assigned
     * to the specified protected object.
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

        return ! $protected_object->device()
            ->where('hub_id', $value)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('template.error.rule.device_is_already_attached');
    }
}
