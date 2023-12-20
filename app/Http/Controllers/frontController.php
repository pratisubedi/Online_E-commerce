<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class frontController extends Controller
{
    public function index(){
       $product=Product::where('is_featured','Yes')->where('status',1)->get();
       $data['products']=$product;
        return view('Front.home',$data);
    }
}
