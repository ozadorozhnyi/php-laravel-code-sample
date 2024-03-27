<?php

namespace App\Http\Requests\Account\ProtectedObject;

use App\Http\Requests\FailedValidationTrait;
use App\Http\Requests\FormRequest;
use App\Models\ProtectedObjectType;

/**
 * Class StoreRequest
 * @package App\Http\Requests\Manage\Resources\User
 */
class StoreRequest extends FormRequest
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

    public function prepareForValidation()
    {
        $this->merge([
            // replace boolean with int for the correctness storing model into database-table with tinyInt type
            'detached_building' => (int)$this->request->getBoolean('detached_building', false)
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type_id' => [
                'bail','required','integer',
                'exists:protected_object_types,id'
            ],

            'city' => 'bail|required|string|max:255',
            'region' => 'bail|required|string|max:255',
            'street' => 'bail|required|string|max:255',
            'house' => 'bail|required|string|max:255',

            'first_name' => 'bail|required|string|min:3|max:255',
            'last_name' => 'bail|required|string|min:3|max:255',
            'father_name' => 'bail|required|string|min:3|max:255',

            'passport' => ['bail','required_without:id_passport','nullable','min:8','max:8'],
            'id_passport' => ['bail','required_without:passport','nullable','min:9','max:9'],
            'passport_date' => ['bail','required', 'date_format:d.m.Y'],
            'passport_issued' => ['bail','required','string','min:3', 'max:255'],
            'id_tax' => ['bail', 'required', 'digits:10'],

            'detached_building' => ['bail', 'required', 'boolean'],
            'number_of_inputs' => ['bail','required','integer','between:1,20'],

            'apartment' => [
                'bail',
                'required_if:type_id,'.implode(',', [
                    ProtectedObjectType::OBJ_FLAT,
                    ProtectedObjectType::OBJ_OFFICE_SPACE,
                    ProtectedObjectType::OBJ_COMMERCIAL_PREMISES,
                ]),
                sprintf("exclude_if:type_id,%s", ProtectedObjectType::OBJ_PRIVATE_HOUSEHOLD),
                'integer','min:1','max:10000'
            ],
            'floor' => [
                'bail',
                'required_if:type_id,'.ProtectedObjectType::OBJ_FLAT,
                sprintf("exclude_if:type_id,%s", implode(',', [
                    ProtectedObjectType::OBJ_OFFICE_SPACE,
                    ProtectedObjectType::OBJ_PRIVATE_HOUSEHOLD,
                    ProtectedObjectType::OBJ_COMMERCIAL_PREMISES
                ])),
                'integer','min:0','max:200'
            ],
            'entrance' => [
                'bail',
                'required_if:type_id,'.ProtectedObjectType::OBJ_FLAT,
                sprintf("exclude_if:type_id,%s", implode(',', [
                    ProtectedObjectType::OBJ_OFFICE_SPACE,
                    ProtectedObjectType::OBJ_PRIVATE_HOUSEHOLD,
                    ProtectedObjectType::OBJ_COMMERCIAL_PREMISES,
                ])),
                'integer','min:1','max:500'
            ],
            'addition' => ['bail','sometimes','string','max:255'],
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        $attrs = [];

        $attrs["type_id"] = __('template.account.protected_object.type_id');
        $attrs["city"] = __('template.account.protected_object.city');
        $attrs["region"] = __('template.account.protected_object.region');
        $attrs["street"] = __('template.account.protected_object.street');
        $attrs["house"] = __('template.account.protected_object.house');
        $attrs["detached_building"] = __('template.account.protected_object.detached_building');
        $attrs["number_of_inputs"] = __('template.account.protected_object.number_of_inputs');
        $attrs["apartment"] = __('template.account.protected_object.apartment');
        $attrs["floor"] = __('template.account.protected_object.floor');
        $attrs["entrance"] = __('template.account.protected_object.entrance');
        $attrs["addition"] = __('template.account.protected_object.addition');
        $attrs["passport"] = __('template.account.protected_object.passport');
        $attrs["id_passport"] = __('template.account.protected_object.id_passport');
        $attrs["passport_issued"] = __('template.account.protected_object.passport_issued');
        $attrs["passport_date"] = __('template.account.protected_object.passport_date');
        $attrs["id_tax"] = __('template.account.protected_object.id_tax');

        return $attrs;
    }

    public function messages()
    {
        return [
            'passport.required_without' => __('validation.required_without_passport', ['id_passport' => __('template.account.protected_object.id_passport')]),
            'passport_issued.required_with' => __('validation.required'),
            'passport_date.required_with' => __('validation.required'),
            'id_tax.required_with' => __('validation.required'),
            'id_passport.required_without' => __('validation.required'),
        ];
    }
}
