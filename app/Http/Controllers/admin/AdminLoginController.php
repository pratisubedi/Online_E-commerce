<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function index(){
        return view("/admin/login");
    }
    public function authenticate(Request $request){
        $validator=validator::make($request->all(),[
            "email"=> "required|email|exists:users",
            "password"=> "required"
        ],[
            'email.exists'=> 'Email is not exits in system',
        ]);
        if($validator->passes()){
                if(Auth::guard('admin')
                    ->attempt(['email'=>$request->email,'password'=>$request->password],
                        $request->get('remember'))){
                            $admin=Auth::guard('admin')->user();
                            if($admin->role==1){
                                return redirect()->route('admin.dashboard')->with('success','Login is successfull');
                            }else{
                                Auth::guard('admin')->logout();
                                return redirect()->route('admin.login')->with('error','you are not authorized to access admin panel ');
                            }
                            
            }else{
                return redirect()->route('admin.login')->with('fail','Email or password is incorrect');
            }
            
        }else{
            return redirect()->route('admin.login')->withErrors($validator)->withInput(request()->only('email'));
        }
    }

    
}

