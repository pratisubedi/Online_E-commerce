<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;

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
}
