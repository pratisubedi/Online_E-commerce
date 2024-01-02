<?php

namespace App\Http\Controllers;

use App\Models\BrandsModel;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class shopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
        {

            $subCategorySelected='';
            $categorySelected='';
            $brandsArray=[];

            $categories = Category::orderBy('name', 'ASC')->with('sub_category')->where('status', 1)->get();
            $brands = BrandsModel::orderBy('name', 'ASC')->where('status', 1)->get();

            $products = Product::where('status', 1);

            // Applying filters on category
            if (!empty($categorySlug)) {
                $category = Category::where('slug', $categorySlug)->first();
                if ($category) {
                    $products = $products->where('category_id', $category->id);
                    $categorySelected = $category->id;
                }
            }
            //Applying filter on subcategory
            if (!empty($subCategorySlug)) {
                $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
                if ($subCategory) {
                    $products = $products->where('sub_category_id', $subCategory->id);
                    $subCategorySelected = $subCategory->id;
                }
            }

            // Applying filter on brands
            if(!empty($request->get('brand'))){
                $brandsArray=explode(',', $request->get('brand'));
                $products= $products->whereIn('brands_models_id', $brandsArray);
            }
            // Order products by ID in descending order
            $products = $products->orderBy('id', 'DESC')->get();

            $data['categories'] = $categories;
            $data['brands'] = $brands;
            $data['products'] = $products;
            $data['categorySelected'] = $categorySelected;
            $data['subCategorySelected'] = $subCategorySelected;
            $data['brandsArray'] = $brandsArray;

            return view('Front.shop', $data);
        }

}
