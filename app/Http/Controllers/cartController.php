<?php

namespace App\Http\Controllers;

use App\Mail\orderEmail;
use App\Models\couponsModel;
use App\Models\customerAddress;
use App\Models\order;
use App\Models\orderItem;
use Illuminate\Http\Request;
use App\Models\Product;
Use App\Models\country;
use App\Models\shipping;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Session;

class cartController extends Controller
{
    public function addToCart(Request $request){
       // $product=Product::with('product_images')->find($request->id);
        $product=Product::find($request->id);
        if($product==null){
            return response()->json([
                "status"=> "error",
                "message"=> "Record not found"
            ]);
        }

        if(Cart::count()>0){
            //echo "Product already in cart";
            //product found in cart
            // check if this product already in the cart
            // Return as message that product already added in your cart
            //if product not found in the cart, then add product in cart
            $cartContent=Cart::content();
            $productAlreadyExist=false;
            foreach($cartContent as $item){
                if($item->id==$product->id){
                    $productAlreadyExist=true;
                }
            }
            if($productAlreadyExist==false){
                 //Cart::add($product->id, $product->title, 1, $product->price,['productImage'=>(!empty($product->product_images))? $product->product_images->first():'']);
                Cart::add($product->id, $product->title, 1, $product->price);
                $status=true;
                $message= $product->title." added in cart";
            }else{
                $status=false;
                $message= $product->title." already added in cart";
            }

        }else{
            echo "Cart is empty noe adding a product in cart";
            //Cart::add($product->id, $product->title, 1, $product->price,['productImage'=>(!empty($product->product_images))? $product->product_images->first():'']);
            Cart::add($product->id, $product->title, 1, $product->price);
            $status=true;
            $message= $product->title."added in cart";
        }
        return response()->json([
            "status"=> $status,
            "message"=> $message,
        ]);
        //Cart::add('293ad', 'Product 1', 1, 9.99);
    }

    public function cart(){
       // dd(Cart::content());
       $cartContent=Cart::content();
       $data['cartContent']=$cartContent;
        return view("Front.cart",$data);
    }

    public function updateCart(Request $request){
        $rowId=$request->rowId;
        $qty=$request->qty;

        //Check qty available in stock
        $itemInfo=Cart::get($rowId);
        $product=Product::find($itemInfo->id);
         if($product->track_qty== 'Yes'){
            if( $qty<= $product->qty){
                Cart::update( $rowId, $qty);
                if(Cart::update($rowId,$qty)==true){
                    $newQty=$product->qty-$qty;
                    $product=Product::find($product->id);
                    $product->qty=$newQty;
                    $product->update();
                }

                $message='cart updated successfully';
                $status=true;
            }else{
                $message='Requested quantity ('.$qty.') not avaliable in stock';
                $status=false;
                session()->flash('error', $message);
            }
         }else{
            Cart::update( $rowId, $qty);
                $message='cart updated successfully';
                $status=true;
                session()->flash('success', $message);
         }
        return response()->json([
            'status'=> $status,
            'message'=> $message,
        ]);

    }
    public function deleteCart(Request $request){
        $rowId=$request->rowId;
        $itemInfo=Cart::get($rowId);
        if($itemInfo==null){
            session()->flash('error', 'Item not found in cart');
            return response()->json([
                "status"=> false,
                "message"=> 'Item not found in cart',
            ]);
        }
        Cart::remove( $rowId );
        session()->flash('success', 'Item  successfully deleted from cart');
            return response()->json([
                "status"=> true,
                "message"=> 'Item  successfully deleted from cart',
            ]);
    }

    public function checkout(){
        // if cart is empty redirect to cart page
        $discount=0;
        if(Cart::count()==0){
            return redirect()->route('Front.cart');
        }
        // if user is not  login
        if(Auth::check()==false){
            if(!session()->has('url.intended')){
                session(['url.intended'=>url()->current()]);
            }
            return redirect()->route('account.login');
        }
        session()->forget('url.intended');
        $id=Auth::user()->id;

        $customerAddress = CustomerAddress::where('user_id', $id)->first();
        $countries=country::orderBY('id','desc')->get();

        //Shipping calculate
        $userCountry = $customerAddress->country_id ?? 1;
        //$userCountry=$customerAddress->country_id;
        if($userCountry==NULL){
            $userCountry=1;
        }
        $shippingInfo=shipping::where('country_id',$userCountry)->first();
        // dd($shippingInfo);
        //shipping charge
        $totalqty=0;
        $totalShippingCharges=0;
        foreach(Cart::content() as $item){
            $totalqty +=$item->qty;
        }
        $totalShippingCharges=$totalqty*$shippingInfo->amount;
        $grandTotal=Cart::subtotal(2,'.','')+$totalShippingCharges;
        $data['countries']=$countries;
        $data['customerAddress']=$customerAddress;
        $data['totalShippingCharges']=$totalShippingCharges;
        $data['grandTotal']=$grandTotal;
        $data['discount']=$discount;
        return view('Front.checkout',$data);
    }

    public function processCheckout(Request $request){


        //Apply validator
        $rules=[
            'first_name'=> 'required',
            'last_name'=> 'required',
            'email'=> 'required',
            'address'=> 'required',
            'city'=> 'required',
            'state'=> 'required',
            'zip'=> 'required',
            'mobile'=> 'required',
        ];
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json([
                'status'=> false,
                'errors'=>$validator->errors(),
            ]);
        }

        // Save customer address on Customer_Addresses table
        $user=Auth::user();
        customerAddress::updateOrCreate([
            'user_id'=>$user->id],
            [
                'user_id'=> $user->id,
                'first_name'=> $request->first_name,
                'last_name'=> $request->last_name,
                'email'=> $request->email,
                'address'=> $request->address,
                'city'=> $request->city,
                'state'=> $request->state,
                'zip'=> $request->zip,
                'Appartment'=> $request->appartment,
                'Mobile'=> $request->mobile,
                'country_id'=> $request->country,
            ]);


        // Save orders on orders table

            if($request->payment_method== 'cod'){
                $discount=0;
                //Applying shipping
                $subTotal = Cart::subtotal(2, '.', '');
                $shippingInfo = Shipping::where('country_id', $request->country_id)->first();
                $totalqty=0;
                if(session()->has('code')){
                    $code=session()->get('code');
                    $coupon_code=$code->code;
                    $coupon_code_id=$code->id;
                    if($code->type== 'percent'){
                        $discount=($code->discount_amount/100)*$subTotal;
                    }else{
                        $discount=$code->discount_amount;
                    }
                }
                if($shippingInfo != null){
                    $totalShippingCharges = $shippingInfo->amount*$totalqty;
                    $grandTotal = $totalShippingCharges + ($subTotal-$discount);
                }else{
                     $subTotal = Cart::subtotal(2, '.', '');
                    $shippingInfo = Shipping::where('country_id', 'rest_of_world')->first();
                    foreach(Cart::content() as $item){
                        $totalqty +=$item->qty;
                    }
                    if($shippingInfo != null){
                        $totalShippingCharges = $shippingInfo->amount*$totalqty;
                        $grandTotal = $totalShippingCharges + ($subTotal-$discount);
                    }
                }

                $subTotal=Cart::subtotal(2,'.','');
                $grandTotal=$subTotal+$totalShippingCharges;
                $order= new order();
                $order->subTotal=$subTotal;
                $order->user_id=$user->id;
                $order->shipping=$totalShippingCharges;
                $order->discount=$discount;
                $order->coupon_code='null';
                $order->coupon_code_id='0';
                $order->grand_total=$grandTotal;
                $order->payment_status='not-paid';
                $order->status='pending';
                $order->first_name=$request->first_name;
                $order->last_name=$request->last_name;
                $order->email=$request->email;
                $order->address=$request->address;
                $order->city=$request->city;
                $order->state=$request->state;
                $order->zip=$request->zip;
                $order->Appartment=$request->appartment;
                $order->Mobile=$request->mobile;
                $order->country_id=$request->country;
                $order->notes=$request->order_notes;
                $order->save();
                //step-4 store order items in order items table
                foreach(Cart::content() as $item){
                    $orderItem=new orderItem();
                    $orderItem->order_id= $order->id;
                    $orderItem->product_id=$item->id;
                    $orderItem->name=$item->name;
                    $orderItem->qty=$item->qty;
                    $orderItem->price=$item->price;
                    $orderItem->total= $item->total;
                    $orderItem->save();


                    //update product stock
                    $productData=Product::find($item->id);
                    if($productData->track_qty=='Yes'){
                        $currentQty=$productData->qty;
                        $updatedQty=$currentQty-$item->qty;
                        $productData->qty=$updatedQty;
                        $productData->update();
                    }

                }
                // send order email
                app('App\Helpers\Helper')->orderEmail($order->id,'customer');
                //app('App\Mail')->orderEmail($order->id);
                Cart::destroy();
                Session::forget('code');
                session()->flash('success','You have successfully placed your order');

                return response()->json([
                    'message'=>'You have successfully placed your order.',
                    'status'=>true,
                    'orderId'=>$order->id,
                ]);

            }else{

            }


    }

    public function thankYou($orderId){
        $order=$orderId;
        $data['order']=$order;
        return view('Front.thankyou',$data);
    }
    public function getOrderSummary(Request $request){

        $subTotal = Cart::subtotal(2, '.', '');
        $discount = 0;
        //Apply Discount Here
        if(session()->has('code')){
            $code=session()->get('code');
            if($code->type== 'percent'){
                $discount=($code->discount_amount/100)*$subTotal;
            }else{
                $discount=$code->discount_amount;
            }
        }

        //get order Summary
        if($request->country_id > 0){
            $subTotal = Cart::subtotal(2, '.', '');
            $shippingInfo = Shipping::where('country_id', $request->country_id)->first();
            $totalqty=0;
            foreach(Cart::content() as $item){
                $totalqty +=$item->qty;
            }
            if($shippingInfo != null){
                $totalShippingCharges = $shippingInfo->amount*$totalqty;
                $grandTotal = $totalShippingCharges + ($subTotal-$discount);

                return response()->json([
                    'status' => true,
                    'totalShippingCharges' => number_format($totalShippingCharges,2),
                    'discount'=> number_format($discount,2),
                    'grandTotal' => number_format($grandTotal,2),
                ]);
            }else{
                 $subTotal = Cart::subtotal(2, '.', '');
                $shippingInfo = Shipping::where('country_id', 'rest_of_world')->first();
                foreach(Cart::content() as $item){
                    $totalqty +=$item->qty;
                }
                if($shippingInfo != null){
                    $totalShippingCharges = $shippingInfo->amount*$totalqty;
                    $grandTotal = $totalShippingCharges + ($subTotal-$discount);

                    return response()->json([
                        'status' => true,
                        'totalShippingCharges' => number_format($totalShippingCharges,2),
                        'discount'=> number_format($discount,2),
                        'grandTotal' => number_format($grandTotal,2),
                    ]);
                }
            }
        } else {
            $subTotal = Cart::subtotal(2, '.', '');
            return response()->json([
                'status'=> true,
                'grandTotal'=> number_format($subTotal-$discount,2),
                'discount'=> number_format($discount,2),
                'totalShippingCharges'=> number_format(0,2),
            ]);
        }
    }

    public  function applyDiscount(Request $request){
        $code=couponsModel::where('code',$request->code)->first();
        if($code == null){
            return response()->json([
                'status'=> false,
                'message'=> 'Invalid discount Coupons',
            ]);
        }

        //Check if coupons start date is  valid or not
        $now=Carbon::now();
        if($code->statrts_at !=''){
              $startDate=Carbon::createFromFormat('Y-m-d H:i:s', $code->statrts_at);
              if($now->lt($startDate)){
                return response()->json([
                    'status'=> false,
                    'message'=> 'Coupons is not start. Please wait few time',
                ]);
              }
        }
        // Expire date valid
        if($code->expire_at !=''){
            $endDate=Carbon::createFromFormat('Y-m-d H:i:s', $code->expire_at);
            if($now->gt($endDate)){
              return response()->json([
                  'status'=> false,
                  'message'=> 'Coupons is expire. Please try another coupons',
              ]);
            }
        }
        //Validate how many times coupon code used
        if($code->max_uses>0){
            $couponUsed=order::where('coupon_code_id',$code->id)->count();
            if($couponUsed >=$code->max_uses){
                return response()->json([
                    'status'=> false,
                    'message'=> 'Coupons  used times finished',
                ]);
            }
        }
        //Validate how many times user used this  coupon code
        if($code->max_uses_user> 0){
            $couponsUsedByUser=order::where(['coupon_code_id'=>$code->id,'user_id'=>Auth::user()->id])->count();
            if($couponsUsedByUser >=$code->max_uses_user){
                return response()->json([
                    'status'=> false,
                    'message'=> 'You have already used this coupons',
                ]);
            }
        }
        //Min Amount check
        $subtotal=Cart::subtotal(2,'.','');
        if($code->min_amount>0){
            if($subtotal<$code->min_amount){
                return response()->json([
                    'status'=> false,
                    'message' => 'You must shop above ' . $code->min_amount . ' amount to use coupons',
                ]);
            }
        }
        session()->put('code',$code);
        return $this->getOrderSummary($request);
    }

    public function removeCoupons(Request $request){
        session()->forget('code');
        return $this->getOrderSummary($request);
    }

}
