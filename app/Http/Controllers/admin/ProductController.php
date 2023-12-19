<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BrandsModel;
use App\Models\Category;
use App\Models\PoductImage;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    public function index(Request $request){
        $products= Product::latest('id');
        if($request->get('keyword')){
            $products=$products->where('title','like','%'.$request->keyword.'%');
        }
        $products=$products->paginate(5);
        $data['products']=$products;
        return view('admin.product.index',$data);
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
            'sku'=>'required|unique:products',
            'track_qty'=>'required|in:Yes,No',
            'category'=>'required|numeric',
            'is_featured'=>'required|in:Yes,No',
        ];
        if(!empty($request->track_qty)&& $request->track_qty=='Yes'){
            $rules['qty']='required|numeric';
        }
        $validator=Validator::make($request->all(),$rules);
        if($validator->passes()){
           dd($request->all());
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
            if (!empty($request->image_array)) {
                // foreach ($request->image_array as $temp_image_id) {
                //     $tempImageInfo = TempImage::find($temp_image_id);
            
                //     if ($tempImageInfo) { // Check if TempImage with given ID exists
                //         $extArray = explode('.', $tempImageInfo->name);
                //         $ext = last($extArray); // like jpg png
            
                //         $productImage = new PoductImage();
                //         $productImage->product_id = $product->id;
                //         $productImage->image = 'NULL'; // This line might not be necessary, depending on your requirements
            
                //         $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                //         $productImage->image = $imageName;
                //         $productImage->save();
                //     }
                // }
                dd($request->all());
            }
            $request->session()->flash('success','Product created successfully done------');
            return response()->json([
                'status'=>true,
                'message'=>'Product created successfully done------'
            ]);
            
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }

    public function edit($id,Request $request){
        $data=[];
        $categories=Category::orderBy('name','ASC')->get();
        $brands=BrandsModel::orderBy('name','ASC')->get();
        $products=Product::find($id);
        $subCategory=SubCategory::where('category_id',$products->category_id)->get();
        $data['categories']=$categories;
        $data['brands']=$brands;
        $data['products']=$products;
        $data['subCategory']=$subCategory;
        return view('admin.product.edit',$data);
    }
}
