<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandsController;
use App\Http\Controllers\admin\categoryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\sub_category;
use App\Http\Controllers\admin\TempImagesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/admin/login',[AdminLoginController::class,'index'])->name('admin.login');

Route::group(['prefix'=> 'admin'], function () {
    Route::group(['middleware'=>'admin.guest'],function(){
        Route::get('/login',[AdminLoginController::class,'index'])->name('admin.login');
        Route::post('/authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');
    });
    Route::group(['middleware'=>'admin.auth'],function(){
        //Route::post('/authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');
        Route::get('/dashboard',[HomeController::class,'index'])->name('admin.dashboard');
        Route::get('/logout',[HomeController::class,'logout'])->name('admin.logout');
        //categor routes
        Route::get('/categories/create',[categoryController::class,'create'])->name('categories.create');
        Route::get('/categories',[categoryController::class,'index'])->name('categories.index');
        Route::post('/categories/store',[categoryController::class,'store'])->name('categories.store');
        Route::get('/categories/{category}/edit',[categoryController::class,'edit'])->name('categories.edit');
        Route::put('/categories/{category}',[categoryController::class,'update'])->name('categories.update');
        Route::delete('/categories/{category}',[categoryController::class,'destroy'])->name('categories.delete');


        //Subcategory routes
        Route::get('/sub-categories/create',[sub_category::class,'create'])->name('sub-categories.create');
        Route::post('/sub-categories/store',[sub_category::class,'store'])->name('sub-categories.store');
        Route::get('/sub-categories',[sub_category::class,'index'])->name('sub-categories.index');
        Route::get('/sub-categories/{category}/edit',[sub_category::class,'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{category}',[sub_category::class,'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{category}',[sub_category::class,'destroy'])->name('sub_categories.delete');

        // Routes for brands 
        Route::get('/brands/create',[BrandsController::class,'create'])->name('brands.create');
        Route::post('/brand/store',[BrandsController::class,'store'])->name('brand.store');
        Route::get('/brands',[BrandsController::class,'index'])->name('brands.index');
        Route::get('/brands/{brand}/edit',[BrandsController::class,'edit'])->name('brands.edit');
        Route::put('/brands/{brand}',[BrandsController::class,'update'])->name('brands.update');
        Route::delete('/delete/{category}',[BrandsController::class,'destroy'])->name('brands.delete');



        //temp-image.create
        Route::post('/upload-temp-image',[TempImagesController::class,'create'])->name('temp-image.create');
        Route::get('/getSlug',function(Request $request){
                $slug='';
                if(!empty($request->title )){
                    $slug=Str::slug($request->title);
                }
                //return back()->with($slug); 
                return response()->json([
                    'status'=>true,
                    'slug'=>$slug
                ]);
        })->name('getSlug');
    });

});