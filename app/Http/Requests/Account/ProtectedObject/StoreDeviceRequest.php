<?php

namespace App\Http\Requests\Account\ProtectedObject;

use App\Http\Requests\FailedValidationTrait;
use App\Rules\DeviceIsAssigned;
use App\Rules\IsOnlyOneDevice;
use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceRequest extends FormRequest
{
    use FailedValidationTrait;

    /**
     * @var array
     */
    private array $rules = [];

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
        $protected_object_id = (int)request()->protected_object_id;

        $this->rules = [
            'protected_object_id' => [
                'bail',
                'required',
                'integer',
                'exists:App\Models\ProtectedObject,id'
            ],
            'hub_id' => [
                'bail',
                'required',
                new DeviceIsAssigned($protected_object_id),
                new IsOnlyOneDevice($protected_object_id),
            ],
            'manufacturer' => [
                'required',
                'in:ajax' // add a new manufacturer alias there
            ],
        ];

        if ('ajax' === request()->manufacturer) {
            $this->addAjaxRelatedRules();
        }

        return $this->rules;
    }

    public function attributes()
    {
        $attrs = [];

        $attrs["protected_object_id"] = __('template.account.devices.protected_object_id');
        $attrs["hub_id"] = __('template.account.devices.hub_id');
        $attrs["manufacturer"] = __('template.account.devices.manufacturer');

        return $attrs;
    }

    /**
     * Add validation rules, specific only to the Ajax manufacturer.
     * Because every manufacturers generates and assign it's own identifiers
     * to their devices.
     */
    private function addAjaxRelatedRules():void
    {
        array_push($this->rules['hub_id'],
            'alpha_num', 'size:8');
    }
}
