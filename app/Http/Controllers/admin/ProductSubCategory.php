<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductSubCategory extends Controller
{
    public function index(Request $request){
        $category_id = $request->input('category_id');
            $subCategories=SubCategory::where('category_id',$category_id)
                ->orderBy('name','ASC')->get();
            return response()->json([
                'status'=>true,
                'subCategories'=>$subCategories
            ]);
    }
}
