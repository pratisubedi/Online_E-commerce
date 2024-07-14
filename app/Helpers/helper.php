<?php
namespace App\Helpers;
use App\Mail\orderEmail;
use App\Models\Category;
use App\Models\order;
use App\Models\PoductImage;
use App\Models\staticPage;
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
    public function orderEmail($orderId, $userType='customer'){
        $order=order::where('id', $orderId)->with('items')->first();
        if($userType=='customer'){
            $subject='Thanks  for your order.';
            $email=$order->email;
        }else{
            $subject='You have a recevied an order';
            $email=env('ADMIN_EMAIL');
        }
        $mailData=[
            'subject'=>$subject,
            'order'=> $order,
            'userType'=>$userType,
        ];
        Mail::to($email)->send(new orderEmail($mailData));

    }

    function staticPage(){
        $pages=staticPage::orderBy('name','ASC')->get();

        return $pages;
    }
}

?>
