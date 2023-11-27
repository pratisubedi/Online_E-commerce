<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\File;
//use App\Models\Category;

class sub_category extends Controller
{
    public function index(Request $request){
        $subcategories=subcategory::select('sub_categories.*','categories.name as categoryName')
        ->latest('sub_categories.id')
        ->leftjoin('categories','categories.id','sub_categories.category_id');


        if(!empty($request->get('keyword'))){
            $categories=$subcategories->where('sub_categories.name','like','%'.$request->get('keyword').'%');
            $categories=$subcategories->orwhere('categories.name','like','%'.$request->get('keyword').'%');
        }
        $subcategories =$subcategories->paginate(5);
        return view('admin.subcategory.list',compact('subcategories'));
    }
    public function create(){
        $categories=Category::orderBy('name','ASC')->get();
        $data['categories']=$categories;
        return view('admin.subcategory.create',$data);
    }
    public function store(Request $request){
        $validator =validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:sub_categories',
            'category'=>'required',
            'status'=>'required'
        ]);
        if($validator->passes()){
            $subCategory= new SubCategory();
            $subCategory->name=$request->name;
            $subCategory->slug=$request->slug;
            $subCategory->status=$request->status;
            $subCategory->category_id=$request->category;
            $subCategory->save();
            $request->session()->flash('success','sub categor created success');
            return response()->json([
                'status'=>true,
                'message'=>'sub categor created success'
            ]);
        }else{
            return response([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }
    public function edit($id,Request $request){
        $subcategory=SubCategory::find($id);
        if(empty($subcategory)){
            $request->session()->flash('error','Record not found');
            return redirect()->route('sub-categories.index');
        }
        $categories=Category::orderBy('name','ASC')->get();
        $data['categories']=$categories;
        $data['subcategory']=$subcategory;
        return view('admin.subcategory.edit',$data);
    }

    public function update($id, Request $request){
        $sub_category= SubCategory::find($id);
        if(empty($sub_category)){
            return response()->json([
                'status'=>false,
                'message'=>'id not found'
            ]);
        }
        $validator =validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:sub_categories,slug,'.$sub_category->id.',id',
            //'slug'=>'required|unique:sub_categories',
            'category'=>'required',
            'status'=>'required'
        ]);
        if($validator->passes()){
            $subCategory= new SubCategory();
            $subCategory->name=$request->name;
            $subCategory->slug=$request->slug;
            $subCategory->status=$request->status;
            $subCategory->category_id=$request->category;
            $subCategory->save();
            $request->session()->flash('success','sub category created success');
            
            return response()->json([
                'status'=>true,
                'message'=>'sub category created success'
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }
}
