<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandsController;
use App\Http\Controllers\admin\categoryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductSubCategory;
use App\Http\Controllers\admin\shippingController;
use App\Http\Controllers\admin\sub_category;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\cartController;
use App\Http\Controllers\frontController;
use App\Http\Controllers\shopController;
use App\Http\Controllers\authController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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


//Route for frontend shop page
Route::get('/',[frontController::class,'index'])->name('Front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}',[shopController::class,'index'])->name('Front.shop');
Route::get('/product/{slug}',[shopController::class,'products'])->name('front.product');
Route::get('/admin/login',[AdminLoginController::class,'index'])->name('admin.login');

//Route for cart page
Route::get('/cart',[cartController::class,'cart'])->name('Front.cart');
Route::post('/add-to-cart',[cartController::class,'addToCart'])->name('Front.addToCart');
Route::post('/update-Cart',[cartController::class,'updateCart'])->name('Front.updateCart');
Route::post('/delete-cart',[cartController::class,'deleteCart'])->name('Front.deleteCart');
Route::get('/checkout',[cartController::class,'checkout'])->name('Front.checkout');
Route::post('/process-checkout',[cartController::class,'processCheckout'])->name('Front.processCheckout');
Route::get('/thank/{orderId}',[cartController::class,'thankYou'])->name('Front.thank');

// Route for user account
//Route::get('/logout',[authController::class,'logout'])->name('account.logout');
Route::group(['prefix'=>'account'],function(){
    Route::group(['middleeare'=>'guest'],function(){
        //Route  for register and login customer
        Route::get('/register',[authController::class,'register'])->name('account.register');
        Route::get('/login',[authController::class,'login'])->name('account.login');
        Route::post('/processRegister',[authController::class,'processRegister'])->name('account.processRegister');
        Route::post('/login-authenticate',[authController::class,'authenticate'])->name('account.authenticate');
    });

    Route::group(['middleware'=>'auth'],function(){
        Route::get('/logout',[authController::class,'logout'])->name('account.logout');

        Route::get('/profile',[authController::class,'profile'])->name('account.profile');
    });
});


//Route for admin panel

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

        // Routes for products
        Route::get('/products/create',[ProductController::class,'create'])->name('products.create');
        Route::post('/product/store',[ProductController::class,'store'])->name('products.store');
        Route::get('/product-subcategories/index',[ProductSubCategory::class,'index'])->name('product-subcategories.index');
        Route::get('/products',[ProductController::class,'index'])->name('products.index');
        Route::get('/products/{brand}/edit',[ProductController::class,'edit'])->name('products.edit');
        Route::get('/get-product',[ProductController::class,'getProducts'])->name('product-getProducts');

        //shipping Route
        Route::get('/shipping/create',[shippingController::class,'create'])->name('shipping.create');
        Route::post('/shipping/store',[shippingController::class,'store'])->name('shipping.store');
        Route::get('/shipping/edit/{id}',[shippingController::class,'edit'])->name('shipping.edit');
        Route::post('/shipping/edit/{id}',[shippingController::class,'update'])->name('shipping.update');
        Route::delete('/shipping/delete/{id}',[shippingController::class,'destroy'])->name('shiping.destroy');
        Route::post('/get-order-summary',[cartController::class,'getOrderSummary'])->name('shipping.getOrderSummary');

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
