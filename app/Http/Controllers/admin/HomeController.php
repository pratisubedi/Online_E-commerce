<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
        // $totalAmount=order::get('grand_total');
        // foreach($totalAmount as $total){
        //     $totalSale=0;
        //     $totalSale=$total+$totalSale;
        // }
        $totalOrder=order::count();
        $totalPendingOrder=order::where('status','=','pending')->count();
        $totalDeliveredOrder=order::where('status','=','delivered')->count();
        $totalShippedOrder=order::where('status','=','shipped')->count();
        $totalProducts=Product::count();
        $Customer=User::count();
        $totalCustomer=$Customer-1;
        $data['totalOrder']=$totalOrder;
        $data['totalProducts']=$totalProducts;
        $data['totalCustomer']=$totalCustomer;
        $data['totalPendingOrder']=$totalPendingOrder;
        $data['totalDeliveredOrder']=$totalDeliveredOrder;
        $data['totalShippedOrder']=$totalShippedOrder;
        return view('admin.dashboard',$data);
        // $admin=Auth::guard()->user();
        // echo"Welcome  ".$admin->name.'<a herf="'.route('admin.logout').'"> Logout</a>';
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
