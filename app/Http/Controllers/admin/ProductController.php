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
use Intervention\Image\ImageManager;
//use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Drivers\Gd\Driver;

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
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
            'short_description'=> 'required',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product = new Product;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty ?? 0; // Set default value if not provided
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brands_models_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->short_description= $request->short_description;
            $product->shipping_return= $request->shipping_return;
            $product->save();

            // If the 'image_array' is an array and not empty
            // if (is_array($request->image_array) && count($request->image_array) > 0) {
            //     foreach ($request->image_array as $temp_image_id) {
            //         $tempImageInfo = TempImage::find($temp_image_id);
            //         if ($tempImageInfo) {
            //             $extArray = explode('.', $tempImageInfo->name);
            //             $ext = last($extArray);
            //             $productImage = new PoductImage(); // Corrected class name
            //             $productImage->product_id = $product->id;
            //             $productImage->image = null; // Use null instead of 'NULL'
            //             $productImage->save();
            //             $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
            //             $productImage->image = $imageName;
            //             $productImage->save();
            //             $manager = new ImageManager(Driver::class);
            //             $tempImageInfo = TempImage::find($temp_image_id);
            //             $sourcePath=public_path().'/temp/'.$tempImageInfo->name;
            //             $destinationPath = public_path().'/temp/product/large'.$tempImageInfo->name;
            //             $image = $manager->read($sourcePath);
            //             $image=$image->resize(1400,null);
            //             $image->save($destinationPath);

            //             // Small Image
            //             $sourcePath=public_path().'/temp/'.$tempImageInfo->name;
            //             $destinationPath = public_path().'/temp/product/small'.$tempImageInfo->name;
            //             $image = $manager->read($sourcePath);
            //             $image=$image->resize(1400,null);
            //             $image->save($destinationPath);
            //         }
            //     }
            //     //generating product thumbnail
            //     //for large
            // }

            $request->session()->flash('success', 'Product created successfully done------');
            return response()->json([
                'status' => true,
                'message' => 'Product created successfully done------'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
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
    public function update($id, Request $request)
    {
        $product = Product::find($id);

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,' . $product->id . ',id',
            'price' => 'required',
            'sku' => 'required|unique:products,sku,' . $product->id . ',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
            'short_description'=> 'required',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            // Update the product attributes
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brands_models_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->short_description= $request->short_description;
            $product->shipping_return=$request->shipping_return;
            $product->save();

            // Flash message and response
            $request->session()->flash('success', 'Product updated successfully done------');
            return response()->json([
                'status' => true,
                'message' => 'Product Updated successfully done------'
            ]);

        } else {
            // Validation failed, return error response
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function getProducts(Request $request){
        if($request->term !=''){
            $product=Product::where('name','like','%'.$request->term.'%')->get();

        }
    }
}
