<?php
namespace App\Helpers;
use App\Mail\orderEmail;
use App\Models\Category;
use App\Models\order;
use App\Models\PoductImage;
use Illuminate\Support\Facades\Mail;

class Helper{
    public function getCategories(){
        return Category::orderBy('name','ASC')
        ->with('sub_category')
        ->where('status',1)
        ->where('showHome','Yes')->get();
    }
    public function getProductImage($productId){
        return PoductImage::where('product_id', $productId)->first();
    }
    public function orderEmail($orderId){
        $order=order::where('id', $orderId)->with('items')->first();
        $mailData=[
            'subject'=>'Thanks  for your order.',
            'order'=> $order,
        ];
        Mail::to($order->email)->send(new orderEmail($mailData));

    }
}

?>
