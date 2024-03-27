<?php

namespace App\Http\Requests\Payment;

use App\Exceptions\FailedValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Payment\Card\{OnlyOneLinked, AlreadyLinked};
use Carbon\Carbon;

class StoreCard extends FormRequest
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
        $token_lifetime_format = config('payment.card_token_lifetime_format');

        $before_or_equal_date = Carbon::now()->addYear(
            (int)config('payment.card_token_lifetime_max_years')
        );

        return [
            'type' => [
                'bail',
                'required',
                'in:visa,mastercard',
            ],
            'last4digits' => [
                'bail',
                'required',
                'integer',
                'digits:4'
            ],
            'token' => [
                'bail',
                'required',
                'alpha_num',
                'size:40',
                new AlreadyLinked(request()->token),
                new OnlyOneLinked(),
                'unique:App\Models\Payment\Card,token'
            ],
            'token_lifetime' => [
                'bail',
                'required',
                "date_format:{$token_lifetime_format}",
                "before_or_equal:{$before_or_equal_date}",
            ],
        ];
    }

    public function attributes()
    {
        $attrs = [];

        $attrs["type"] = __('template.payment.card.type');
        $attrs["last4digits"] = __('template.payment.card.last4digits');
        $attrs["token"] = __('template.payment.card.token');
        $attrs["token_lifetime"] = __('template.payment.card.token_lifetime');

        return $attrs;
    }

    public function failedValidation(Validator $validator)
    {
        throw new FailedValidationException($validator);
    }
}
