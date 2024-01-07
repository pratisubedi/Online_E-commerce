<?php
namespace App\Helpers;
use App\Models\Category;

class Helper{
public function getCategories(){
    return Category::orderBy('name','ASC')
    ->with('sub_category')
    ->where('status',1)
    ->where('showHome','Yes')->get();
}
}

?>
