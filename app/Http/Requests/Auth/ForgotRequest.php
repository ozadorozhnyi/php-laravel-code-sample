<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;

/**
 * Class ForgotRequest
 * @package App\Http\Requests\Manage\Resources\User
 */
class ForgotRequest extends FormRequest
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
            'email' => 'required|email|exists:users',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        $attrs = [];
        $attrs["email"] = 'Email';
        return $attrs;
    }
}
