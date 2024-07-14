<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Hash;

class userController extends Controller
{
    public function index(Request $request){
        $users=User::latest();
        if(!empty($request->get('keyword'))){
            $users=$users->where('name','like','%'.$request->get('keyword').'%');
            $users=$users->orWhere('email','like','%'.$request->get('keyword').'%');
        }
        $users=$users->paginate(5);

        $data['users']=$users;
        return view('admin.users.list',$data);
    }

    public function create(Request $request){
        return view('admin.users.create');
    }

    public function store(Request $request){
        $validator=validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'phone'=>'required',
            'password'=>'required'
        ]);

        if($validator->passes()){
            $users=new User();
            $users->name=$request->name;
            $users->email=$request->email;
            $users->status=$request->status;
            $users->phone=$request->phone;
            $users->password=Hash::make($request->password);
            $users->save();
            session()->flash('success','User create successfully');
            return response()->json([
                'status'=>true,
                'success'=>'User create successfully',
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors(),
            ]);
        }
    }
    public function edit($id){
        $users=User::find($id);
        $data['users']=$users;
        return view('admin.users.edit',$data);
    }

    public function updateUser(Request $request,$id){
        $user=User::find($id);
        if($user==null){
            $message='User not found';
            session()->flash('error',$message);
            return response()->json([
                'status'=>true,
                'message'=>$message,
            ]);
        }
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.$id.',id',
            'phone'=>'required',
        ]);

        if($validator->passes()){
            $user->name=$request->name;
            $user->email=$request->email;
            $user->phone=$request->phone;
            $user->status=$request->status;
            if($request->password !=''){
                $user->password=Hash::make($request->password);
            }
            $user->update();
            session()->flash('success','User was update successfully');
            return response()->json([
                'status'=>true,
                'success'=>'User was update successfully',
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors(),
            ]);
        }
    }

    public function deleteUser($id){
        $user=User::find($id);
        $user->delete();
        if($user==true){
            session()->flash('success','User delete success');
            return response()->json([
                'status'=>true,
                'message'=>'User delete success'
            ]);
        }
    }
}
