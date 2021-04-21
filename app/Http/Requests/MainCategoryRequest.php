<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MainCategoryRequest extends FormRequest
{
    /**
     * @var mixed
     */

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
            'photo' =>'required_without:id|mimes:jpg,png,jpeg',
            'category'=>'required|array|min:1',
            'category.*.name'=>'required',
            'category.*.translation_lang'=>'required',
            //'category.*.active'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'هذا الحقل مطلوب',
            'photo.required_without' => 'هذا الحقل مطلوب',
            'mimes' => 'jpg,png,jpeg صيغه الصوره لابد ان تكون ',
        ];
    }
}
