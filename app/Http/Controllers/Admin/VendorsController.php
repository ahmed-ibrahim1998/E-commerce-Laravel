<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use App\Notifications\VendorCreated;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;


class VendorsController extends Controller
{
    public function index() {

         $vendors = Vendor::selection() -> paginate(PAGINATION_COUNT);

        return view('admin.vendors.index',compact('vendors'));

    }

    public function create() {

         $categories = MainCategory::where('translation_of',0) -> active()->get();
        return view('admin.vendors.create',compact('categories'));
    }

    public function store(VendorRequest $request) {

        try {

            if (!$request->has('active'))
                $request -> request -> add(['active' => 0]);
            else
                $request -> request -> add(['active' => 1]);

            $filePath = "";
            if ($request->has('logo')) {

                $filePath = uploadImage('vendors', $request->logo);
            }

            $vendor = Vendor::create([
                'name' => $request ->name,
                'mobile' => $request ->mobile,
                'email' => $request ->email,
                'password' => $request ->password,  // the password is encrypted in model
                'category_id' =>$request ->category_id,
                'active' => $request ->active,
                'address' => $request ->address,
                'logo' => $filePath,
                'latitude'=>$request->latitude,  // هنا بقوله مكانك هذا علي الخريطه عايز تعدله ام لا
                'longitude'=>$request->longitude,
            ]);

            Notification::send($vendor, new VendorCreated($vendor));


            return redirect()->route('admin.vendors')->with(['success' => 'تم الحفظ بنجاح']);

        } catch (\Exception $ex) {
            return $ex;
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله فيما بعد']);
        }
    }

    public function edit($vendor_id) {

        try {
            $vendor = Vendor::selection() -> find($vendor_id);

            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا']);

            $categories = MainCategory::where('translation_of',0) -> active()->get();

            return view('admin.vendors.edit',compact('vendor','categories'));

        } catch (\Exception $ex) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله فيما بعد']);
        }

    }

    public function update(VendorRequest $request ,$vendor_id) {

        try {

            $vendor = Vendor::selection()-> find($vendor_id);

            if (!$vendor)
                return redirect()->route('admin.vendors.edit', $vendor)->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا']);

            DB::beginTransaction();  /// هذه الداله بتستخدم لو عندي
            ///  اكتر من عمليه هعملهم في الداتا بيز او اكتر من ادخال بيانات زي اللي في الاسفل بمعني اخر هيتفادي لو في اي خطا حصل

            // photo
            if ($request->has('logo')) {  // معني انه لو جايلي صوره جديده في الركوست خش نفذ الشرط
                $filePath = uploadImage('vendors', $request->logo);

                Vendor::where('id',$vendor_id)
                    -> update([
                        'logo'=>$filePath
                    ]);
            }

            // active

            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            //password
            $data = $request->except('_token','id','password','logo');

            if ($request->has('password') && !is_null($request->  password)) {
                $data['password'] = $request->password;
            }

            Vendor::where('id',$vendor_id)
                -> update($data);

            DB::commit();   /// احفظ اي حاجه دخلت عندك
            return redirect()->route('admin.vendors')->with(['success' => 'تم تحديث المتجر بنجاح']);

        } catch(\Exception $ex){
            return $ex;
            DB::rollback(); /// لو حصل خطا في ادخال البانات اللي فوق بمعني حاجه دخلت والاخري لم يتم ادخالها
            /// هذه الداله تعمل ررول بااك بمعني لا تسمح بدخول اي حاجه خالص
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله فيما بعد']);

        }

    }

    public function delete($vendors) {

        try {

            $vendors = Vendor::find($vendors);

            if (!$vendors)
                return redirect()->route('admin.vendors')->with(['error'=>'هذا المتجر غير موجود']);

            $image =  Str::after($vendors->logo,'assets/');   // Str::after  هذه الداله بتقطع الاسترنج اللي عندي

            $image =  base_path('public/assets/'.$image);  // base_path  هذه داله تجيب مسار الصوره من علي الجهاز عندي

            unlink($image);  // unlink  this method delete image from folder on server and delete image from database
            // unlink --- http:// هذه الداله مش بتسمح انها تمسح حاجه بداخل مسار اوله

            $vendors->delete();  // else
            return redirect()->route('admin.vendors')->with(['success' => 'تم الحذف بنجاح']);

        }catch (\Exception $ex) {
            return $ex;
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله فيما بعد']);
        }
    }

    public function changeStatus($id) {

        try {

            $vendors = Vendor::find($id);

            if (!$vendors)
                return redirect()->route('admin.vendors')->with(['error'=>'هذا المتجر غير موجود']);

            $status = $vendors ->active == 0 ? 1 : 0;

            $vendors ->update(['active' => $status]);

            // make observer here هتعمل حاجه مهمه اسمها اوبسرفر دي لو بفعل حاجه بتفعل كل حاجه تحتها والعكس صحيح
            //  php artisan make:observe mainCategoryobserver --model=MainCategory   الامر اللي بيتنفذ

            return redirect()->route('admin.vendors')->with(['success' => 'تم تغيير الحاله بنجاح']);


        } catch (\Exception $ex) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله فيما بعد']);
        }

    }

}
