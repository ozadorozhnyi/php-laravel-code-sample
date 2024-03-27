<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;
use App\Rules\Phone;
use App\Rules\PhoneVerify;

/**
 * Class CheckPhoneRequest
 * @package App\Http\Requests\Manage\Resources\User
 */
class CheckPhoneVerifyRequest extends FormRequest
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
            'token' => ['required'],
            'code' => ['required', new PhoneVerify(request()->token)],
        ];
    }
}
