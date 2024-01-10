<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\country;
use App\Models\shipping;
use Illuminate\Http\Request;
use Validator;

class shippingController extends Controller
{
    public function create(){
        $countries= country::get();
        $shippingCharge=shipping::select('shipping_charges.*','countries.name')
            ->leftJoin('countries','countries.id','shipping_charges.country_id')->get();
        $data["countries"]=$countries;
        $data["shippingCharge"]=$shippingCharge;
        return view("Shipping.create",$data);
    }
    public function store(Request $request){
        $validator=validator::make($request->all(),[
            "country"=> "required",
            "amount"=> "required|numeric",
        ]);
        if($validator->passes()){
            $count=shipping::where("country_id",$request->country)->count();
            if($count> 0){
                session()->flash("error","shipping added already added.");
                return response()->json([
                    'status'=>true,
                ]);
            }
            $shipping=new shipping();
            $shipping->country_id=$request->country;
            $shipping->amount=$request->amount;
            $shipping->save();
            session()->flash("success","shipping added successfully");
            return response()->json([
                'status'=>true,
                'message'=> 'Shipping added successfully',
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'message'=> $validator->errors(),
            ]);
        }

    }
    public  function edit($id){
        $shippingCharge=shipping::find($id);

        $countries=country::get();
        $data['countries']=$countries;
        $data['shippingCharge']=$shippingCharge;
        return view('Shipping.edit',$data);
    }

    public function update(Request $request, $id){
        $shippingcharge=shipping::find($id);
        $validator=validator::make($request->all(),[
            'country'=> 'required',
            'amount'=> 'required',
        ]);
        if($validator->passes()){
            $shippingcharge->country_id=$request->country;
            $shippingcharge->amount=$request->amount;
            $shippingcharge->save();
            session()->flash('success','Shiping charge update successfully');

            return response()->json([
                'status'=>true,
                'message'=> 'Shiping charge update successfully',
            ]);
        }
    }
    public function destroy($id){
        $shippingCharge=shipping::find($id);
        $shippingCharge->delete();
        session()->flash('success','Shipping deleted sucessfully');
        return response()->json([
            'status'=>true,
            'message'=> 'Shipping deleted sucessfully',
        ]);
    }
}
