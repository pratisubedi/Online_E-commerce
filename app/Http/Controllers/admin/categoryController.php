<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Models\Category;
use Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as image;
use Intervention\Image\ImageManager;
//use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Drivers\Gd\Driver;
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
            $category->showHome=$request->showHome;
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

                // //Generate Image ThumNail
                //$manager = ImageManager::gd();
                $manager = new ImageManager(Driver::class);
                $dPath=public_path().'/upload/category/thumnail/'.$newImageName;
                //$img=Image::make($sPath);
                // $img=$manager('',$dPath);
                // $img->resize(450,600);
                // $img->save($dPath);
                $image = $manager->read($sPath);
                $image = $image->resizeDown(450, 600);
                $image->save($dPath);


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

    public function edit($categoryid,Request $request){
        $category=Category::find($categoryid);
        if(empty($category)){
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit',compact('category'));

    }
    public function update($categoryid,Request $request){
        $category=Category::find($categoryid);
        if(empty($category)){
            return response()->json([
                'status'=>false,
                'notFound'=>true,
                'message'=>'category not found'
            ]);
        }

        $validator =validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:categories,slug,'.$category->id.',id',
        ]);
        if($validator->passes()){
            $category->fill([
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status,
            ]);
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

                $request->session()->flash('success','category updated successfully');
                return response()->json([
                    'status'=>true,
                    'message'=>'category updated successfully'
                ]);

            }
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }
    public function destroy($categoryid, Request $request){
        $category=Category::find($categoryid);
        if(empty($category)){
            $request->session()->flash('error','category not found');
            return response()->json([
                'status'=>true,
                'message'=>'category not found'
            ]);
            //return redirect()->route('categories.index');
        }


        File::delete(public_path().'/upload/category/'.$category->image);

        $category->delete();
        $request->session()->flash('success','category deleted successfully');
        return response()->json([
            'status'=>true,
            'message'=>'category deleted successfully'
        ]);
    }
}
