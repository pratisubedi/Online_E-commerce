<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\couponsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Validator;

class discountController extends Controller
{
    public function index(Request $request){
        $discountCoupons=couponsModel::latest();
        if(!empty($request->get('keyword'))){
            $discountCoupons=$discountCoupons->where('name','like','%'.$request->get('keyword').'%');
        }
        $data["discountCoupons"] = $discountCoupons;
        return view("admin.coupons.index", $data);
    }
    public function create(){
        return view("admin.coupons.create");
    }
    public function store(Request $request){
        $rules=[
            "code"=> "required|",
            "name"=> "required",
            "description"=> "required",
            "status"=> "required",
            "max_uses"=> "required|numeric",
            "max_uses_user"=> "required|numeric",
            "type"=> "required",
            "discount_amount"=> "required|numeric",
            "min_amount"=> "required|numeric",
            "start_at"=> "required",
            "expire_at"=> "required",
        ];
        $validator=validator::make($request->all(), $rules);
        if($validator->passes()){
            //checking start date
            if(!empty($request->start_at)){
                $now= Carbon::now();
                $startAt=Carbon::createFromFormat("Y-m-d H:i:s", $request->start_at);
                if($startAt->lte($now)==true){
                    return response()->json([
                        'status'=>false,
                        'errors'=>['start_at'=>'start date cannot be less than current date'],
                    ]);
                }
            }
            // checking  expire date
            if(!empty($request->start_at)&& !empty($request->expire_at)){
                $expire= Carbon::createFromFormat("Y-m-d H:i:s", $request->expire_at);
                $startAt=Carbon::createFromFormat("Y-m-d H:i:s", $request->start_at);
                if($expire->gt($startAt)==false){
                    return response()->json([
                        'status'=>false,
                        'errors'=>['expire_at'=>'Expire date must be greater than start date'],
                    ]);
                }
            }
            $coupons= new couponsModel();
            $coupons->code=$request->code;
            $coupons->name=$request->name;
            $coupons->description=$request->description;
            $coupons->status=$request->status;
            $coupons->type=$request->type;
            $coupons->max_uses=$request->max_uses;
            $coupons->max_uses_user=$request->max_uses_user;
            $coupons->discount_amount= $request->discount_amount;
            $coupons->min_amount= $request->min_amount;
            $coupons->starts_at=$request->start_at;
            $coupons->expire_at=$request->expire_at;
            $coupons->save();
            return response()->json([
                'status'=>true,
                'success'=>'Coupons created successfully',
            ]);
        }
        return response()->json([
            'status'=>false,
            'errors'=> $validator->errors(),
        ]);
    }
    public function edit($id){

    }
    public function update(Request $request, $id){

    }
    public function destroy($id){

    }
}
