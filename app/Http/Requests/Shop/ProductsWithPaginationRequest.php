<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\FailedValidationException;
use Illuminate\Contracts\Validation\Validator;

class ProductsWithPaginationRequest extends FormRequest
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
            'size' => [
                'bail',
                'required',
                'integer',
                "between:1,20",
            ],
        ];
    }

    public function attributes()
    {
        $attrs = [];

        $attrs["size"] = __('template.shop.product.size');

        return $attrs;
    }

    public function failedValidation(Validator $validator)
    {
        throw new FailedValidationException($validator);
    }
}
