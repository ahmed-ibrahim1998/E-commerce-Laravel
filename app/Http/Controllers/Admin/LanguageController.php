<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index() {
        $languages = Language::select()->paginate(PAGINATION_COUNT);
        return view('admin.languages.index',compact('languages'));
    }

    public function create() {

        return view('admin.languages.create');
    }

    public function store(LanguageRequest $request) {

        /*Language::create([
            'name'=>$request->name,
            'abbr'=>$request->abbr,
            'direction'=>$request->direction,
            'active'=>$request->active,
        ]);*/

        try {
            Language::create($request->except(['_token']));

            return redirect()->route('admin.languages')->with(['success' => 'تم اضافه اللغه بنجاح']);

           } catch(\Exception $ex) {

            return redirect()->route('admin.languages')->with(['error' => 'خطا في ادخال البيانات اعد المحاوله فيما بعد']);
          }


    }

    public function edit($lang_id) {

        $language = Language::find($lang_id);

        if (!$language) {
            return redirect()->route('admin.languages')->with(['error'=>'هذه اللغه غير موجوده ']);
        }
        return view('admin.languages.edit',compact('language'));

    }

    public function update($lang_id,LanguageRequest $request)
    {
        try {
            $language = Language::find($lang_id);

            if (!$language) {
                return redirect()->route('admin.languages.edit', $lang_id)->with(['error' => 'هذه اللغه غير موجوده ']);
            }
            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            $language->update($request->except('_token'));

                return redirect()->route('admin.languages')->with(['success' => 'تم تحديث اللغة بنجاح']);

            } catch(\Exception $ex){

                return redirect()->route('admin.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);

            }

    }

    public function delete($lang_id) {
        try {
            $language = Language::find($lang_id);
            if (!$language)
                return redirect()->route('admin.languages.edit', $lang_id)->with(['error'=>'اللغه المراد حذفها غير موجوده']);

            $language->delete();
            return redirect()->route('admin.languages')->with(['success' => 'تم حذف اللغة بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);

        }

    }
}
