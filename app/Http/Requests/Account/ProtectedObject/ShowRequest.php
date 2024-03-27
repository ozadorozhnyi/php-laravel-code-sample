<?php

namespace App\Http\Requests\Account\ProtectedObject;

use App\Http\Requests\FailedValidationTrait;
use App\Rules\ProtectedObject\IsOwnedByUser;
use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
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
                'bail',
                'required',
                'integer',
                'exists:App\Models\ProtectedObject,id',
                new IsOwnedByUser($id)
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'id' => __('template.account.protected_object.id'),
        ];
    }
}
