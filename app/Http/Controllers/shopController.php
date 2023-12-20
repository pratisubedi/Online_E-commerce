<?php

namespace App\Http\Controllers;

use App\Models\BrandsModel;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class shopController extends Controller
{
    public function index(){
         $categories=Category::orderBy('name','ASC')->with('sub_category')->where('status',1)->get();
        //$categories = Category::where('status', 1)->with('sub_category')->get();
        $brands=BrandsModel::orderBy('name','ASC')->where('status',1)->get();
        $products=Product::orderBy('id','DESC')->where('status',1)->get();
        $data['categories']=$categories;
        $data['brands']=$brands;
        $data['products']=$products;
        return view('Front.shop',$data);
    }
}
