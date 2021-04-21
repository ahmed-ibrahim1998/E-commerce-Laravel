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
        return true;  //admin guard
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|string|max:100',
            'abbr'=>'required|string|max:10',
            //'active'=>'required|in:0,1',
            'direction'=>'required|in:rtl,ltr',
        ];
    }
    public function messages()
    {
            return [
               /* 'name.required'=>'هذا الحقل مطلوب',
                'name.string'=>'هذا الحقل لابد ان يكون احرف',
                'name.max'=>'هذا الحقل يجب الا يزيد عن 100 احرف',
                'abbr.required'=>'هذا الحقل مطلوب',
                'abbr.string'=>'هذا الحقل لابد ان يكون احرف',
                'abbr.max'=>'هذا الحقل يجب الا يزيد عن 10 احرف',
                'active.required'=>'هذا الحقل مطلوب',
                'active.in'=>'القيم المدخله غير صحيحيه',
                'direction.required'=>'هذا الحقل مطلوب',
                'direction.in'=>'القيم المدخله غير صحيحيه',
                    */
                //  ممكن اختصر كل اللي فوق ده اوحد الكلام
                'required' => 'هذا الحقل مطلوب',
                'in' => 'القيم المدخلة غير صحيحة ',
                'name.string' => 'اسم اللغة لابد ان يكون احرف',
                'abbr.max' => 'هذا الحقل لابد الا يزيد عن 10 احرف ',
                'abbr.string' => 'هذا الحقل لابد ان يكون احرف ',
                'name.max' => 'اسم اللغة لابد الا يزيد عن 100 احرف ',
            ];
    }
}
