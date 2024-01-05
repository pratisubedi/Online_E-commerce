<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class authController extends Controller
{
    public function login(Request $request){
        return view("Front.Account.login");
    }
    public function register(){
        return view("Front.Account.register");
    }

    public function processRegister(Request $request){
       $validator=validator::Make($request->all(),[
             'name'=>'required|min:3',
             'email'=> 'required|email|unique:users',
             'password'=> 'required|confirmed'
       ]);

       if($validator->passes()){
        $user =new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = bcrypt($request->password);
        $user->save();
        session()->flash('success','You are registered successfully.');
        return response()->json([
            'status'=> true,
            'success'=>'validate success',
           ]);
       }else{
        return response()->json([
            'status'=> false,
            'errors'=>$validator->errors(),
           ]);
       }

    }
}
