<?php

namespace App\Http\Controllers;
use App\Models\staticPage;
use App\Models\wishlist;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Http\Request;

class frontController extends Controller
{
    public function index(){
       $product=Product::where('is_featured','Yes')->with('product_images')->where('status',1)->take(200)->get();
       $products=Product::orderBy('id','DESC')->with('product_images')->where('status',1)->get();
       $data['featuredproducts']=$product;
       $data['latestproducts']=$products;
        return view('Front.home',$data);
    }

    public function addToWishList(Request $request){
        if( Auth::check()==false){
            session(['url.intended'=>url()->previous()]);
            return response()->json([
                'status'=>false,
                'message'=>'You are trying to access this wishlist task without login. So login or sign up first',
            ]);
        }

        $product=Product::where('id',$request->id)->first();
        if($product==null){
            return response()->json([
                'status'=>true,
                'message'=>'<div class="alert alert-danger">  not found.</div>',
            ]);
        }

        wishlist::updateOrCreate(
            [
                'user_id'=>Auth::user()->id,
                'product_id'=>$request->id,
            ],
            [
                'user_id'=>Auth::user()->id,
                'product_id'=>$request->id,
            ]
        );
        // $wishlist= new wishlist();
        // $wishlist->user_id=Auth::user()->id;
        // $wishlist->product_id=$request->id;
        // $wishlist->save();

        return response()->json([
            'status'=>true,
            'message'=>'<div class="alert alert-success"><strong>"'.$product->title.'"</strong>Product added in wishList</div>',
        ]);
    }

    public function page($slug){
        $page=staticPage::where('slug',$slug)->first();
        if($page==null){
            abort(404);
        }
        return view('Front.layout.page',[
            'page'=>$page,
        ]);
    }
}
