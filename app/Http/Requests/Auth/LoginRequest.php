<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FailedValidationTrait;
use App\Http\Requests\FormRequest;

/**
 * Class LoginRequest
 * @package App\Http\Requests\Manage\Resources\User
 */
class LoginRequest extends FormRequest
{
    use FailedValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
//            'remember_me' => 'nullable'
        ];
    }
}
