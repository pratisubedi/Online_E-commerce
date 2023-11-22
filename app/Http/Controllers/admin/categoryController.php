<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Validator;

class categoryController extends Controller
{
    public function index(){
        $categories =Category::latest()->paginate(5);
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
            return back()->with('success','product was successfully created  ');
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
