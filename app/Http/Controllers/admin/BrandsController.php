<?php

namespace App\Http\Controllers\Admin; // Namespace should match the folder structure

use App\Http\Controllers\Controller;
use App\Models\BrandsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Correct the namespace for Validator

class BrandsController extends Controller
{
    public function index()
    {
        $brands= BrandsModel::latest();
        $data['brands']=$brands;
        $brands =$brands->paginate(5);
        return view('admin.brands.list',compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required',
            'slug'   => 'required|unique:brands',
            'status' => 'required'
        ]);

        if ($validator->passes()) {
            
            $brand= new BrandsModel();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success', 'Brand was created successfully');

            return response()->json([
                'status'  => true,
                'message' => 'Brand was created successfully'
            ]);
        } else {
            // Change 'success' to 'error' for flash message
            $request->session()->flash('error', 'There was an error creating the brand');

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
