<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\FailedValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\PartnerIsLocatedInCity;

class StoreConsultationRequest extends FormRequest
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
        $partner_id = (int)request()->partner_id;
        $partner_city_id = (int)request()->partner_city_id;

        return [
            'partner_city_id' => [
                'bail',
                'required',
                'integer',
                'exists:App\Models\City,id',
            ],
            'partner_id' => [
                'bail',
                'required',
                'integer',
                'exists:App\Models\Partner,id',
                new PartnerIsLocatedInCity($partner_id, $partner_city_id)
            ],
            'product_id' => [
                'bail',
                'required',
                'integer',
                'exists:App\Models\Shop\Product,id'
            ],
            'count' => [
                'bail',
                'required',
                'integer',
                'between:1,25',
            ],
            'comment' => [
                'bail',
                'nullable',
                'string',
                'max:255'
            ],
        ];
    }

    public function attributes()
    {
        $attrs = [];

        $attrs["partner_id"] = __('template.shop.consultation_request.partner_id');
        $attrs["partner_city_id"] = __('template.shop.consultation_request.partner_city_id');
        $attrs["product_id"] = __('template.shop.consultation_request.product_id');
        $attrs["count"] = __('template.shop.consultation_request.count');
        $attrs["comment"] = __('template.shop.consultation_request.comment');

        return $attrs;
    }

    public function failedValidation(Validator $validator)
    {
        throw new FailedValidationException($validator);
    }

}
