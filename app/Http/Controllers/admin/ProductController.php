<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BrandsModel;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    public function index(){

    }
    public function create(){
        $data=[];
        $categories=Category::orderBy('name','ASC')->get();
        $brands=BrandsModel::orderBy('name','ASC')->get();
        $data['categories']=$categories;
        $data['brands']=$brands;
        return view('admin.product.create',$data);
    }
    public function store(Request $request){
        //dd($request->all());
        $rules=[
            'title'=>'required',
            'slug'=>'required|unique:products',
            'price'=>'required',
            'sku'=>'required',
            'track_qty'=>'required|in:Yes,No',
            'category'=>'required|numeric',
            'is_featured'=>'required|in:Yes,No',
        ];
        if(!empty($request->track_qty)&& $request->track_qty=='Yes'){
            $rules['qty']='required|numeric';
        }
        $validator=validator::make($request->all(),$rules);
        if($validator->passes()){
           //dd($request->all());
            $product=new Product;
            $product->title=$request->title;
            $product->slug=$request->slug;
            $product->description=$request->description;
            $product->price=$request->price;
            $product->compare_price=$request->compare_price;
            $product->sku=$request->sku;
            $product->barcode=$request->barcode;
            $product->track_qty=$request->track_qty;
            $product->qty=$request->qty;
            $product->status=$request->status;
            $product->category_id=$request->category;
            $product->sub_category_id=$request->sub_category;
            $product->brands_models_id=$request->brand;
            $product->is_featured=$request->is_featured;
            $product->save();
            
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }
}
