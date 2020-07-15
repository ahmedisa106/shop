<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'abbr' => ['required', 'string', 'max:10'],
            'direction' => ['required', 'in:rtl,ltr'],
//            'active' => ['required', 'in:0,1'],
        ];
    }

    public function messages()
    {

        return [
            'required' => 'هذا الحقل مطلوب',
            'name.string' => 'هذا الحقل يجب ان يكون أحرف',
            'abbr.string' => 'هذا الحقل يجب ان يكون أحرف',
            'name.max' => 'هذا الحقل يجب الا يزيد عن 100 أحرف',
            'abbr.max' => 'هذا الحقل يجب الا يزيد عن 10 أحرف',
            'in' => 'القيم المدخله غير صحيحه',

        ];
    }
}
