<?php

namespace App\Http\Requests\Account\ProtectedObject;

use App\Http\Requests\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class StorePartnerApplicationRequest extends FormRequest
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
            'partner_id' => [
                'bail',
                'required',
                'integer',
                'exists:App\Models\Partner,id'
            ],
            'protected_object_id' => [
                'bail',
                'required',
                'integer',
                'exists:App\Models\ProtectedObject,id'
            ],
            'region_id' => [
                'bail',
                'required',
                'integer',
                'exists:App\Models\Region,id'
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

        $attrs["partner_id"] = __('template.account.partner_applications.partner_id');
        $attrs["protected_object_id"] = __('template.account.partner_applications.protected_object_id');
        $attrs["region_id"] = __('template.account.partner_applications.region_id');
        $attrs["comment"] = __('template.account.partner_applications.comment');

        return $attrs;
    }
}
