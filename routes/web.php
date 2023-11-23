<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\categoryController;
use App\Http\Controllers\admin\HomeController;
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