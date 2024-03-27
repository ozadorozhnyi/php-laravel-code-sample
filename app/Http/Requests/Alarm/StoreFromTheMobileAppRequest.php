<?php

namespace App\Http\Requests\Alarm;

use App\Http\Requests\FailedValidationTrait;
use App\Rules\ProtectedObject\IsOwnedByUser;
use Illuminate\Foundation\Http\FormRequest;

class StoreFromTheMobileAppRequest extends FormRequest
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
        $protected_object_id = (int)request()->protected_object_id;

        return [
            'protected_object_id' => [
                'bail',
                'required',
                'integer',
                'exists:App\Models\ProtectedObject,id',
                new IsOwnedByUser($protected_object_id)
            ],
        ];
    }
}
