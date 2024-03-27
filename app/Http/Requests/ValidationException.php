<?php

namespace App\Http\Requests;

use Illuminate\Validation\ValidationException as BaseValidationException;

class ValidationException extends BaseValidationException
{
    /**
     * @return array
     */
    public function errors()
    {
        return [
            'title' => 'Error',
            'message' => current($this->validator->errors()->messages())[0] ?? ''
        ];
    }
}
