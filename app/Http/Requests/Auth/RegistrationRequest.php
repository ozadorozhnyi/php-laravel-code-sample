<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;
use App\Rules\IsPhoneVerified;
use App\Rules\Phone;

/**
 * Class RegistrationRequest
 * @package App\Http\Requests\Manage\Resources\User
 */
class RegistrationRequest extends FormRequest
{
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
            'type_id' => 'required|exists:user_types,id',
            'first_name' => 'required',
            'last_name' => 'required',
            'region' => 'required',
            'city' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => ['required', 'unique:users', new Phone, new IsPhoneVerified(request()->phone_token)],
            'password' => 'required|min:6',
            'phone_token' => 'required'
        ];
    }
}
