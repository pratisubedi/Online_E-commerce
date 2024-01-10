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
            $subCategory->showHome=$request->showHome;
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
        $subCategory = SubCategory::find($id);

        if(empty($subCategory)){
            return response()->json([
                'status' => false,
                'message' => 'ID not found',
                'notFound' => true
            ]);
        }

        $validator = validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
            'category' => 'required',
            'status' => 'required'
        ]);

        if($validator->passes()){
            // Update the existing record instead of creating a new one
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->showHome = $request->showHome;
            $subCategory->save();

            $request->session()->flash('success', 'Subcategory updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Subcategory updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id,Request $request){
        $category=SubCategory::find($id);
        if(empty($category)){
            $request->session()->flash('error','category not found');
            return response()->json([
                'status'=>true,
                'message'=>'category not found'
            ]);
            //return redirect()->route('categories.index');
        }
        $category->delete();
        $request->session()->flash('success','Sub category deleted successfully');
        return response()->json([
            'status'=>true,
            'message'=>'Sub category deleted successfully'
        ]);
    }
}
