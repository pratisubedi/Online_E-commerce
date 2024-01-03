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

            //Applying filter on price

            if($request->get('price_max')!='' && $request->get('price_min')!= ''){
                if($request->get('price_max')==5000){
                    $products = $products->whereBetween('price', [intval($request->get('price_min')), 10000000]);
                }else{
                    $products = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);
                }
            }
            // Applying filter on sorting
            if($request->get('sort')) {
                if($request->get('sort')=='latest'){
                    $products = $products->orderBy('id', 'DESC');
                }
                else if($request->get('sort')== 'price_asc'){
                    $products = $products->orderBy('id','ASC');
                }
                else{
                    $products = $products->orderBy('id','DESC');
                }
            }
            $products=$products->paginate(4);
            // Order products by ID in descending order


            $data['categories'] = $categories;
            $data['brands'] = $brands;
            $data['products'] = $products;
            $data['categorySelected'] = $categorySelected;
            $data['subCategorySelected'] = $subCategorySelected;
            $data['brandsArray'] = $brandsArray;
            $data['priceMax'] = (intval($request->get('price_max'))==0) ? 1000:$request->get('price_max');
            $data['priceMin'] = intval($request->get('price_min'));
            $data['sort'] = $request->get('sort');

            return view('Front.shop', $data);
        }

        public function products($slug){
            //echo $slug;
            //$products = Product::where('slug',$slug)->with('product_images')->first();
            $products = Product::where('slug',$slug)->first();
            if($products==null){
                abort(404);
            }
            $data['products'] = $products;
            return view('Front.product', $data);
        }
}
