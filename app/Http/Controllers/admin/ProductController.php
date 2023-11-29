<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BrandsModel;
use App\Models\Category;
use Illuminate\Http\Request;

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

    }
}
