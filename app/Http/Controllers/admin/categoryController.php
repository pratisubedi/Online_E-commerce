<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Validator;

class categoryController extends Controller
{
    public function index(Request $request){
        $categories=Category::latest();
        if(!empty($request->get('keyword'))){
            $categories=$categories->where('name','like','%'.$request->get('keyword').'%');
        }
        $categories =$categories->paginate(5);
        return view('admin.category.list',compact('categories'));
    }

    public function create(){
        return view("admin.category.create");
    }
    public function store(Request $request){
        $validator=Validator::make($request->all(),[
            "name"=> 'required',
            "slug"=>'required|unique:categories'
        ]);

        if($validator->passes()){
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->save();
            return redirect()->route('categories.index')->with('success','product was successfully created  ');
            //return back()->with('success','product was successfully created  ');
        }else{
            return back()->withErrors($validator);
            // response()->json([
            //     'status'=> false,
            //     'errors'=>$validator->errors()
            // ]);
        }
    }

    public function edit(){
        
    }
    public function update(){
        
    }
    public function destroy(){

    }
}
