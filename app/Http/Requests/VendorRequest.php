<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
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
            'logo' => 'required_without:id|mimes:jpg,jpeg,png',
            'name' => 'required|string|max:100',
            'mobile' => 'required|max:100|unique:vendors,mobile,' . $this->id,
            'category_id' => 'required|exists:main_categories,id',
            'email' => 'required|email|unique:vendors,email,' . $this->id,
            'password' => 'required_without:id',
            'address' => 'required|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'هذا الحقل مطلوب',
            'max' => 'هذا الحقل طويل',
            'category_id.exists' => 'القسم غير موجود',
            'email.email' => 'صيغه البريد غير صحيحه',
            'address.string' => 'العنوان لابد  ان يكون أحرف و ارقام',
            'name.string' => 'الاسم لابد  ان يكون أحرف و ارقام',
            'email.unique' => 'البريد مستخدم من قبل',
            'mobile.unique' => 'رقم الهاتف مستخدم من قبل',
            'password.required' => 'كلمه المرور مطلوبه',

        ];
    }
}
