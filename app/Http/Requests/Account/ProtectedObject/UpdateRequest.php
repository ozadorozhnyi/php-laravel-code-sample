<?php

namespace App\Http\Requests\Account\ProtectedObject;

use App\Http\Requests\FailedValidationTrait;
use App\Models\ProtectedObjectType;
use App\Rules\ProtectedObject\IsOwnedByUser;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        $id = (int)request()->id;

        return [
            'id' => [
                'bail','required','integer',
                'exists:App\Models\ProtectedObject,id',
                new IsOwnedByUser($id)
            ],

            'type_id' => [
                'bail','required','integer',
                'exists:protected_object_types,id'
            ],

            'city' => 'bail|required|string|max:255',
            'region' => 'bail|required|string|max:255',
            'street' => 'bail|required|string|max:255',
            'house' => 'bail|required|string|max:255',

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

    public function attributes()
    {
        $attrs = [];

        $attrs["id"] = __('template.account.protected_object.id');
        $attrs["type_id"] = __('template.account.protected_object.type_id');
        $attrs["city"] = __('template.account.protected_object.city');
        $attrs["region"] = __('template.account.protected_object.region');
        $attrs["street"] = __('template.account.protected_object.street');
        $attrs["house"] = __('template.account.protected_object.house');
        $attrs["apartment"] = __('template.account.protected_object.apartment');
        $attrs["floor"] = __('template.account.protected_object.floor');
        $attrs["entrance"] = __('template.account.protected_object.entrance');
        $attrs["detached_building"] = __('template.account.protected_object.detached_building');
        $attrs["number_of_inputs"] = __('template.account.protected_object.number_of_inputs');
        $attrs["addition"] = __('template.account.protected_object.addition');

        return $attrs;
    }
}
