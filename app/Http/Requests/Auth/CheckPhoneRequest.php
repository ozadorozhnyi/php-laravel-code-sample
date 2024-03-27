<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FailedValidationTrait;
use App\Http\Requests\FormRequest;
use App\Rules\Phone;
/**
 * Class CheckPhoneRequest
 * @package App\Http\Requests\Manage\Resources\User
 */
class CheckPhoneRequest extends FormRequest
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
            'phone' => ['required', new Phone(), 'unique:users'],
        ];
    }

}
