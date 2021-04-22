<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\MainCategoriesController;
use App\Http\Controllers\Admin\VendorsController;
use App\Models\MainCategory;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
define('PAGINATION_COUNT',10);

Route::group(['namespace' => 'Admin','middleware'=>'auth:admin'],function() {
    Route::get('/',[DashboardController::class,'index'])->name('admin.dashboard');

    ############################## Start Languages Routes ###################################
    Route::group(['prefix'=>'language'], function() {
        Route::get('/',[LanguageController::class,'index'])->name('admin.languages');
        Route::get('create',[LanguageController::class,'create'])->name('admin.languages.create');
        Route::post('store',[LanguageController::class,'store'])->name('admin.languages.store');
        Route::get('edit/{lang_id}',[LanguageController::class,'edit'])->name('admin.languages.edit');
        Route::post('update/{lang_id}',[LanguageController::class,'update'])->name('admin.languages.update');
        Route::get('delete/{lang_id}',[LanguageController::class,'delete'])->name('admin.languages.delete');

    });
    ############################## End Languages Routes ###################################

    ############################## Start Main Categories Routes ###################################
    Route::group(['prefix'=>'main_categories'], function() {
        Route::get('/',[MainCategoriesController::class,'index'])->name('admin.maincategories');
        Route::get('create',[MainCategoriesController::class,'create'])->name('admin.maincategories.create');
        Route::post('store',[MainCategoriesController::class,'store'])->name('admin.maincategories.store');
        Route::get('edit/{cat_id}',[MainCategoriesController::class,'edit'])->name('admin.maincategories.edit');
        Route::post('update/{cat_id}',[MainCategoriesController::class,'update'])->name('admin.maincategories.update');
        Route::get('delete/{cat_id}',[MainCategoriesController::class,'delete'])->name('admin.maincategories.delete');
        Route::get('change_status/{cat_id}',[MainCategoriesController::class, 'changeStatus'])->name('admin.maincategories.status');
    });
    ############################## End Main Categories Routes #####################################

    ############################## Start Sub Categories Routes ###################################
    Route::group(['prefix'=>'sub_categories'], function() {
        Route::get('/',[SubCategoriesController::class,'index'])->name('admin.subcategories');
        Route::get('create',[SubCategoriesController::class,'create'])->name('admin.subcategories.create');
        Route::post('store',[SubCategoriesController::class,'store'])->name('admin.subcategories.store');
        Route::get('edit/{cat_id}',[SubCategoriesController::class,'edit'])->name('admin.subcategories.edit');
        Route::post('update/{cat_id}',[SubCategoriesController::class,'update'])->name('admin.subcategories.update');
        Route::get('delete/{cat_id}',[SubCategoriesController::class,'delete'])->name('admin.subcategories.delete');
        Route::get('change_status/{cat_id}',[SubCategoriesController::class, 'changeStatus'])->name('admin.subcategories.status');
    });
    ############################## End Sub Categories Routes #####################################

    ############################## Start Vendors Routes ###################################
    Route::group(['prefix'=>'vendors'], function() {
        Route::get('/',[VendorsController::class,'index'])->name('admin.vendors');
        Route::get('create',[VendorsController::class,'create'])->name('admin.vendors.create');
        Route::post('store',[VendorsController::class,'store'])->name('admin.vendors.store');
        Route::get('edit/{cat_id}',[VendorsController::class,'edit'])->name('admin.vendors.edit');
        Route::post('update/{cat_id}',[VendorsController::class,'update'])->name('admin.vendors.update');
        Route::get('delete/{cat_id}',[VendorsController::class,'delete'])->name('admin.vendors.delete');
        Route::get('change_status/{cat_id}',[VendorsController::class, 'changeStatus'])->name('admin.vendors.status');
    });
    ############################## End Vendors Routes #####################################
});


Route::group(['namespace' => 'Admin', 'middleware' => 'guest:admin'], function () {
    Route::get('login', [LoginController::class,'getLogin'])->name('get.admin.login');
    Route::post('login', [LoginController::class,'login'])->name('admin.login');
});





################### Test Route ##########################################################
Route::get('sub_category',function () {

    $maincategory  = MainCategory::find(66);

    return $maincategory->subCategories;
});

Route::get('main_category',function () {

    $subcategory = \App\Models\SubCategory::find(1);

    return $subcategory->mainCategory;
});
