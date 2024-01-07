<?php

namespace App\Http\Controllers;

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
}
