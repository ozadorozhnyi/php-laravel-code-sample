<?php

namespace App\Http\Requests\Alarm;

use Illuminate\Foundation\Http\FormRequest;

class PanelSignalStoreRequest extends FormRequest
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
            'ID' => 'required|integer|min:1', // 555
            'EventID' => 'required|string|in:E,R',
            'EventCode' => 'required|integer',
        ];
    }
    public function attributes()
    {
        $attrs = [];

        $attrs["ID"] = __('template.alarm.panel_signal.object_id');
        $attrs["EventID"] = __('template.alarm.panel_signal.event_type');
        $attrs["EventCode"] = __('template.alarm.panel_signal.event_code');

        return $attrs;
    }
}
