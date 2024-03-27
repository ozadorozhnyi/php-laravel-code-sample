<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\FailedValidationException;
use Illuminate\Contracts\Validation\Validator;

class ProductWithRelatedRequest extends FormRequest
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
            'product_id' => [
                'bail',
                'required',
                'integer',
                'exists:App\Models\Shop\Product,id'
            ],
            'related_qty' => [
                'bail',
                'required',
                'integer',
                "between:0,50",
            ],
        ];
    }

    public function attributes()
    {
        $attrs = [];

        $attrs["product_id"] = __('template.shop.product.product_id');
        $attrs["related_qty"] = __('template.shop.product.related_qty');

        return $attrs;
    }

    public function failedValidation(Validator $validator)
    {
        throw new FailedValidationException($validator);
    }
}
