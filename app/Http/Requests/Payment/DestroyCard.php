<?php

namespace App\Http\Requests\Payment;

use App\Exceptions\FailedValidationException;
use App\Rules\Payment\Card\BelongsToTheUser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class DestroyCard extends FormRequest
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
            'token' => [
                'bail',
                'required',
                'alpha_num',
                'size:40',
                'exists:App\Models\Payment\Card,token',
                new BelongsToTheUser(request()->token),
            ],
        ];
    }

    public function attributes()
    {
        $attrs = [];

        $attrs["token"] = __('template.payment.card.token');

        return $attrs;
    }

    public function failedValidation(Validator $validator)
    {
        throw new FailedValidationException($validator);
    }
}
