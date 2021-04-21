<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use DB;
use Illuminate\Support\Str;

class MainCategoriesController extends Controller
{
    public function index() {
        $default_lang = get_default_lang(); // To Show Default Language To Admin   == Arabic

        $categories = MainCategory::where('translation_lang',$default_lang) ->selection() ->get();

        return view('admin.mainCategories.index',compact('categories'));
    }

    public function create() {
        return view('admin.mainCategories.create');
    }

    public function store(MainCategoryRequest $request)
    {
        try {
         //return $request;

        $main_categories = collect($request->category);

        $filter = $main_categories->filter(function ($value, $key) {
            return $value['translation_lang'] == get_default_lang();
        });

         $default_category = array_values($filter->all()) [0];

        $filePath = "";
        if ($request->has('photo')) {

            $filePath = uploadImage('maincategories', $request->photo);
        }

        DB::beginTransaction();  /// هذه الداله بتستخدم لو عندي
            ///  اكتر من عمليه هعملهم في الداتا بيز او اكتر من ادخال بيانات زي اللي في الاسفل بمعني اخر هيتفادي لو في اي خطا حصل

        $default_category_id = MainCategory::insertGetId([
            'translation_lang' => $default_category['translation_lang'],
            'translation_of' => 0,
            'name' => $default_category['name'],
            'slug' => $default_category['name'],
            'photo' => $filePath
        ]);

        // If Data Enterd Not Arabic

        $categories = $main_categories->filter(function ($value, $key) {
            return $value['translation_lang'] != get_default_lang();
        });


        if (isset($categories) && $categories->count()) {

            $categories_arr = [];
            foreach ($categories as $category) {
                $categories_arr[] = [
                    'translation_lang' => $category['translation_lang'],
                    'translation_of' => $default_category_id,
                    'name' => $category['name'],
                    'slug' => $category['name'],
                    'photo' => $filePath
                ];
            }
            MainCategory::insert($categories_arr);
        }

            DB::commit();   /// احفظ اي حاجه دخلت عندك
            ///
            return redirect()->route('admin.maincategories')->with(['success'=>'تم الحفظ بنجاح']);

        }catch (\Exception $ex) {
            DB::rollback(); /// لو حصل خطا في ادخال البانات اللي فوق بمعني حاجه دخلت والاخري لم يتم ادخالها
            /// هذه الداله تعمل ررول بااك بمعني لا تسمح بدخول اي حاجه خالص
            return redirect()->route('admin.maincategories')->with(['error'=>'حدث خطا ما برجاء المحاوله فيما بعد']);
        }

    }

    public function edit($maincategory_id) {

        // get specific categories and its translation
         $mainCategory = MainCategory::with('categories')
                                        -> selection()
                                        ->find($maincategory_id);

        if (!$mainCategory)

            return redirect()->route('admin.maincategories')->with(['error'=>'هذا القسم غير موجود']);

        return view('admin.mainCategories.edit',compact('mainCategory'));
    }

    public function update($maincategory_id,MainCategoryRequest $request) {


        try {
            $main_category = MainCategory::find($maincategory_id);

            if (!$main_category)

                return redirect()->route('admin.maincategories')->with(['error'=>'هذا القسم غير موجود']);

            // update values
              $category = array_values($request -> category) [0];

              if (!$request->has('category.0.active'))
                  $request -> request -> add(['active' => 0]);
              else
                  $request -> request -> add(['active' => 1]);


            MainCategory::where('id',$maincategory_id)
                -> update([
                    'name' => $category['name'],
                    'active'=> $request->active,
                ]);

              // save  new image

            if ($request->has('photo')) {  // معني انه اختار صوره جديده
                $filePath = uploadImage('maincategories', $request->photo);
                MainCategory::where('id',$maincategory_id)
                    -> update([
                        'photo'=>$filePath
                    ]);
            }

            return redirect()->route('admin.maincategories')->with(['success' => 'تم التحديث بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله فيما بعد']);
        }
    }

    public function delete($maincategory_id) {

        try {

        $mainCategory = MainCategory::find($maincategory_id);

        if (!$mainCategory)
            return redirect()->route('admin.maincategories')->with(['error'=>'هذا القسم غير موجود']);

        $vendors = $mainCategory -> vendors();   // هذا السطر هيرجع تجار القسم

        if (isset($vendors) && $vendors->count() > 0 ) {
            return redirect()->route('admin.maincategories')->with(['error'=>'لايمكن حذف هذا القسم لانه يحتوي علي العديد من التجار']);
        }

             $image =  Str::after($mainCategory->photo,'assets/');   // Str::after  هذه الداله بتقطع الاسترنج اللي عندي

             $image =  base_path('public/assets/'.$image);  // base_path  هذه داله تجيب مسار الصوره من علي الجهاز عندي

             unlink($image);  // unlink  this method delete image from folder on server and delete image from database
            // unlink --- http:// هذه الداله مش بتسمح انها تمسح حاجه بداخل مسار اوله

            // this action delete default translation and all translation by another language
            $mainCategory ->categories()->delete();

            $mainCategory->delete();  // else
             return redirect()->route('admin.maincategories')->with(['success' => 'تم الحذف بنجاح']);

        }catch (\Exception $ex) {
            return $ex;
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله فيما بعد']);
        }
    }

    public function changeStatus($id) {

        try {

            $mainCategory = MainCategory::find($id);

            if (!$mainCategory)
                return redirect()->route('admin.maincategories')->with(['error'=>'هذا القسم غير موجود']);

            $status = $mainCategory ->active == 0 ? 1 : 0;

            $mainCategory ->update(['active' => $status]);

            // make observer here هتعمل حاجه مهمه اسمها اوبسرفر دي لو بفعل حاجه بتفعل كل حاجه تحتها والعكس صحيح
            //  php artisan make:observe mainCategoryobserver --model=MainCategory   الامر اللي بيتنفذ

            return redirect()->route('admin.maincategories')->with(['success' => 'تم تغيير الحاله بنجاح']);


        } catch (\Exception $ex) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله فيما بعد']);
        }

    }
}
