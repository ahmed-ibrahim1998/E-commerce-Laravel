<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Admin;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function getLogin() {

        return view('admin.auth.login');

    }
//    public function login(LoginRequest $request) {
//        // make validation in LoginRequest
//         /*
//        $messages = [
//            'email.required'=>'البريد الالكتروني مطلوب.',
//            'email.email'=>'ادخل بريد الكتروني صالح.',
//            'email.password'=>'كلمه المرور مطلوبه.',
//        ];
//        $validator =Validator::make($request->all(),[
//            'email'=>'required|email',
//            'password'=>'password',
//        ],$messages);*/
//
//        $remember_me = $request->has('remember_me') ? true : false;
//
//        if (auth()->guard('admin')->attempt(['email' => $request->input("email"), 'password' => $request->input('password')], $remember_me)) {
//            // notify()->success('تم الدخول بنجاح  ');
//            return redirect() -> route('admin.dashboard');
//        }
//        // notify()->error('خطا في البيانات  برجاء المجاولة مجدا ');
//        return redirect()->back()->with(['error' => 'هناك خطا بالبيانات']);
//
//    }

    public function login(LoginRequest $request){

        $remember_me = $request->has('remember_me') ? true : false;

        if (auth()->guard('admin')->attempt(['email' => $request->input("email"), 'password' => $request->input("password")], $remember_me)) {
            // notify()->success('تم الدخول بنجاح  ');
            return redirect() -> route('admin.dashboard');
        }
        // notify()->error('خطا في البيانات  برجاء المجاولة مجدا ');
        return redirect()->back()->with(['error' => 'هناك خطا بالبيانات']);
    }
}
