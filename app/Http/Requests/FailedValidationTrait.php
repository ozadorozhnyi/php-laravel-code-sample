<?php

namespace App\Http\Requests;

use App\Exceptions\FailedValidationException;
use Illuminate\Contracts\Validation\Validator;

trait FailedValidationTrait
{
    public function failedValidation(Validator $validator)
    {
        throw new FailedValidationException($validator);
    }
}
