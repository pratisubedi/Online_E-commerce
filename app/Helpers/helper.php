<?php
namespace App\Helpers;
use App\Models\Category;
use App\Models\PoductImage;

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
}

?>
