<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use  Illuminate\Support\Facades\Auth;
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

    public function authenticate(Request $request){
        $validator=Validator::Make($request->all(),[
            'email' => 'required|email',
            'password'=> 'required',
        ]);
        if($validator->passes()){
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))){
                return redirect()->route('account.profile');
            }else{
                session()->flash('error','Either email or password is incorrect');
                return redirect()->route('account.login')->withInput($request->only('email'));
            }
        }else{
            return redirect()->route('account.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }
    public function profile(){
        return view('Front.Account.profile');
    }
}
