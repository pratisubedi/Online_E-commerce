<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Models\Category;
use Validator;
use Illuminate\Support\Facades\File;
use Image;
use GdImage;

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
            if(!empty($request->image_id)){
                $tempImage= TempImage::find($request->image_id);
                $extArray=explode('.',$tempImage->name);
                $ext=last($extArray);

                $newImageName=$category->id.'.'.$ext;
                $sPath=public_path().'/temp/'.$tempImage->name;
                $dPath=public_path().'/upload/category/'.$newImageName;
                File::copy($sPath,$dPath);
                $category->image =$newImageName;
                $category->save();

                //Generate Image ThumNail
                $dPath=public_path().'/upload/category/thumnail/'.$newImageName;
                $img=Image::make($sPath);
                $img->resize(450,600);
                $img->save($dPath);

            }

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
