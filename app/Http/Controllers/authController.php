<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\orderItem;
use App\Models\User;
use App\Models\wishlist;
use Illuminate\Http\Request;
use Validator;
use Hash;
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
                if(session()->has('url.intended')){
                    return redirect(session()->get('url.intended'));
                }
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
        $profileInformations=User::where('id',Auth::user()->id)->first();
        $data['profileInformations']=$profileInformations;
        return view('Front.Account.profile',$data);
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('account.login')->with('success','You are Successfully logged out from  system');
    }

    //my order controller
    public function order(){
        $user=Auth::user();
        $orders=order::where('user_id',$user->id)->orderBy('created_at','DESC')->get();
        $data['orders']=$orders;
        return view('Front.Account.myOrder',$data);
    }

    public function order_detail($id){
        $user=Auth::user();
        $orders=order::where('user_id',$user->id)->where('id',$id)->first();
        $orderItems=orderItem::where('order_id',$id)->get();
        $data['orderItems']=$orderItems;
        $data['orders']= $orders;
        return view('Front.account.order-detail', $data);
    }

    public function wishList(){

        $wishlists=wishlist::where('user_id',Auth::user()->id)->with('product')->get();

        $data=[];
        $data['wishlists']=$wishlists;
        return view('Front.account.wishlist',$data);
    }

    public function removeProductWishlist(Request $request){
        $wishlistProduct=wishlist::where('user_id',Auth::user()->id)->where('product_id',$request->id)->first();
        if($wishlistProduct==null){
            session()->flash('error','Product already removed.');
            return response()->json([
                'status'=>true,
            ]);
        }else{
            $wishlistProduct=wishlist::where('user_id',Auth::user()->id)->where('product_id',$request->id)->delete();
            session()->flash('success','Product removed successfully from wishlist.');
            return response()->json([
                'status'=>true,
            ]);
        }
    }

    public function profileUpdate(Request $request){
        $userId=Auth::user()->id;
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.$userId.',id',
            'phone'=>'required'
        ]);

        if($validator->passes()){
            $id=Auth::user()->id;
            $user=User::where('id',$id)->first();
            $user->name=$request->name;
            $user->email=$request->email;
            $user->phone=$request->phone;
            $user->update();

            session()->flash('success','Profile update successfully');
            return response()->json([
                'status'=>true,
                'message'=>'user profile update successfully.',
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors(),
            ]);
        }
    }

    public function showchangePassword(){
        return view('Front.Account.changePassword');
    }

    public function changePassword(Request $request){
        $validator=validator::make($request->all(),[
            'old_password'=>'required',
            'new_password'=>'required|min:3|same:confirm_password',
            'confirm_password'=>'required',
        ]);

        if($validator->passes()){
            $user=User::select('id','password')->where('id',Auth::user()->id)->first();
            if(!Hash::check($request->old_password, $user->password)){
                session()->flash('error','Your old password is incorrect, please try again.');
                return response()->json([
                    'status'=>true,
                ]);
            }
            User::where('id',$user->id)->update([
                'password'=>Hash::make($request->new_password),
            ]);

            session()->flash('success','You have successfully changed your password');
            return response()->json([
                'status'=>true,
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors(),
            ]);
        }
    }
}
