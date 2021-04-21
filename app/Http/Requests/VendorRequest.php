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
            'logo'      => 'required_without:id|mimes:jpg,png,jpeg',
            'name'      => 'required|string|max:100',
            'mobile'    => 'required|max:100|unique:vendors,mobile,'.$this ->id, //  // لازم احدد اليوزر اللي هعدل عليه بهذه الطريقه لانه فريد من نوعه unique
            'email'     => 'required|email|unique:vendors,email,'.$this ->id,   // لازم احدد اليوزر اللي هعدل عليه بهذه الطريقه لانه فريد من نوعه  unique
            'password'     => 'required_without:id',
            'category_id'  => 'required|exists:main_categories,id', //بمعني ان يكون بداخل هذا الجدول في قاعده البيانات وبالرقم الموجود في هذا الجدول
            'address'   => 'required|string|max:300',
        ];
    }

    public function messages() {

        return  [
            'required' => 'هذا الحقل مطلوب',
            'max' => 'هذا الحقل طويل جدا',
            'min' => 'هذا الحقل قصير جدا',
            'category_id.exists' => ' هذا القسم غير موجود',
            'email.email' => 'صيغه الايميل غير صحيحه يجب ان ينتهي ب gmail.com@ ',
            'address.string' => 'العنوان يجب ان يكون حروف او ارقام',
            'name.string' => 'الاسم يجب ان يكون حروف او ارقام',
            'logo.required_without' => 'الصوره مطلوبه',
            'mobile.unique' => 'رقم الهاتف مستخدم من قيل',
            'email.unique' => 'البريد الاكتروني مستخدم من قيل',
            'password.string' => 'كلمه المرور يجب ان يكون حروف او ارقام',
            'password.required_without' => 'كلمه المرور يجب مطلوبه',
        ];
    }


}
