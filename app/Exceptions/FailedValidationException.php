<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Contracts\Validation\Validator;

class FailedValidationException extends Exception
{
    protected $validator;
    protected $code = Response::HTTP_UNPROCESSABLE_ENTITY;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function render()
    {
        return response()->json([
            'title' => __('api.response.status.error.title'),
            'message' => $this->validator->errors()->first(),
        ], $this->code);
    }
}
