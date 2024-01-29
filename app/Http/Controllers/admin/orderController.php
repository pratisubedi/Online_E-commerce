<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\orderItem;
use Illuminate\Http\Request;

class orderController extends Controller
{
    public function index(Request $request){
        $orders=order::latest('orders.created_at')->select('orders.*','users.name','users.email');
        $orders=$orders->leftjoin('users','users.id','orders.user_id');
        if($request->get('keyword') != ''){
            $orders=$orders->where('users.name','like','%'.$request->keyword.'%');
            $orders=$orders->orWhere('users.email','like','%'.$request->keyword.'%');
            $orders=$orders->orWhere('orders.id','like','%'.$request->keyword.'%');
        }

        $orders=$orders->paginate(10);
        $data['orders']= $orders;
        return view("admin.orders.list",$data);
    }
    public function detail($orderId){
        $orders=order::select('orders.*','countries.name as countryName')
                ->leftjoin('countries','countries.id','orders.country_id')
                 ->where("orders.id",$orderId)->first();
        $orderItems=orderItem::where('order_id',$orderId)->get();
        $data['orderItems']= $orderItems;
        $data['orders']= $orders;
        return view('admin.orders.orderDetails',$data);
    }
    public function changeOderStataus(Request $request,$orderId){
        $order=order::find($orderId);
        $order->status=$request->status;
        $order->shipped_date=$request->shipped_date;
        $order->save();
        session()->flash('success','Order Status changed Successfully');
        return response()->json([
            'status'=> 'true',
            'message'=> 'Order Status changed Successfully',
        ]);
    }
    public function sendInvoiceEmail(Request $request, $orderId){
        echo 'hello invoice ';
    }
}
