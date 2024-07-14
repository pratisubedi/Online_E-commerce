<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\staticPage;
use Illuminate\Http\Request;
use Validator;

class staticPagesController extends Controller
{
    public function index(Request $request){
        $pages=staticPage::latest();
        if($request->keyword !=''){
            $pages=$pages->where('name','like','%'.$request->keyword.'%');
        }
        $pages=$pages->paginate(10);
        $data['pages']=$pages;
        return view('admin.staticPage.index',$data);
    }

    public function create(){
        return view('admin.staticPage.create');
    }

    public function store(Request $request){

        $validator=validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required',
            'content'=>'required',
        ]);

        if($validator->passes()){
            $pages=new staticPage();
            $pages->name=$request->name;
            $pages->content=$request->content;
            $pages->slug=$request->slug;
            $pages->save();

            session()->flash('success','Static Page ' . $request->name . ' Create successfully');
            return response()->json([
                'status'=>true,
                'success'=>'Static Pages Create successfully',
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors(),
            ]);
        }

    }

    public function edit($id){
        $page=staticPage::find($id);
        $data['page']=$page;

        return view('admin.staticPage.edit',$data);
    }
    public function update(Request $request,$id){
        $page=staticPage::find($id);
        if($page==null){
            session()->flash('error','No Data found');
            return response()->json([
                'status'=>true,
            ]);
        }

        $validator=validator::make($request->all(),[
            'name'=>'required',
            'content'=>'required',
        ]);

        if($validator->passes()){
            $page->name=$request->name;
            $page->slug=$request->slug;
            $page->content=$request->content;
            $page->update();

            session()->flash('success','Page '.$page->name.' is updated successfully.');
            return response()->json([
                'status'=>true,
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors(),
            ]);
        }
    }

    public function destroy($id){
        $page=staticPage::find($id);
        $page->delete();

        session()->flash('success','Page '.$id.'  Was sucessfully deleted');
        return response()->json([
            'status'=>true,
        ]);
    }
}
