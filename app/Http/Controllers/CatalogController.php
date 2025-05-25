<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\URL;
use Inertia\Response;
use App\Models\Category;
use App\Models\Banner;
use Illuminate\Support\Arr;

class CatalogController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home/Index', [
            'categories' =>Category::with('children.children')->whereNull('parent_id')->get(),
            'banners' =>Banner::where('is_active',1)->orderBy('position','asc')->get()
        ]);
    }
    public function getByCategory($id)
    {
        // dd(Request::all());
        $data =Request::all();
        $banners = Banner::where('is_active',1)->orderBy('position','asc')->get()   ;         
        $category = Category::with(['filters.subFilters','children'])->findOrFail($id);
        $filtersWithCounts = $category->filters->map(function ($filter) use($id){
            $filterProductCount = $filter->products()->where('category_id',$id)->count();

            $subFiltersWithCounts = $filter->subFilters->map(function ($subFilter)use($id) {
                return [
                    'id' => $subFilter->id,
                    'name_ru' => $subFilter->name_ru,
                    'name_arm' => $subFilter->name_arm,
                    'name_en' => $subFilter->name_en,
                    'product_count' => $subFilter->products()->where('category_id',$id)->count(),
                ];
            });
            return [
                'id' => $filter->id,
                'name_ru' => $filter->name_ru,
                'name_arm' => $filter->name_arm,
                'name_en' => $filter->name_en,
                'type' => $filter->type,
                'product_count' => $filterProductCount,
                'sub_filters' => $subFiltersWithCounts,
            ];
        });
    
        $products = Product::where('category_id',$id)->where('count','!=',0);
        $products = $this->filterData($products,$data);
        $products= $products->paginate(20);
        $prices = Product::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();

            return Inertia::render('Catalog/Index', [
                'categories' =>Category::with('children.children')->whereNull('parent_id')->get(),
                'textBanners' =>$banners,
                'category' =>$category,
                'products' =>$products,
                'filtersWithCounts' =>$filtersWithCounts,
                'prices'=>$prices,
            ]);
    }
    public function filterData($products,$data){
        if (count($data)==0) {
           return $products;
        }else{
            if (isset($data['new'])) {
                $products =$products->where('is_new',1);
            }
            if (isset($data['bestseller'])) {
                $products =$products->where('is_bestseller',1);
            }
            if (isset($data['discount'])) {
                $products =$products->where('discount','>',0);
                
            }
            if (isset($data['price'])) {
                // $products =$products->where('price','>=',$data['price']['min'])->where('price','=<',$data['price']['max']);
                $products =$products->whereBetween('price',[$data['price']['min'],$data['price']['max']]);
                
            }
            if (isset($data['filters'])) {
                $subFilterIds = Arr::flatten($data['filters']);
                $products =$products->whereHas('subFilters', function ($query) use ($subFilterIds) {
                    $query->whereIn('sub_filter_id', $subFilterIds);
                });
            }
            return  $products;
        }
     
    }
    public function getBySubCategory($id)
    {
        $data =Request::all();

        $banners = Banner::where('is_active',1)->orderBy('position','asc')->get()   ;         
        $subCategory =Category::where('id',$id)->first();
        $category = Category::with(['filters.subFilters'])->findOrFail($subCategory->parent_id);
        $filtersWithCounts = $category->filters->map(function ($filter) use($id) {
            $filterProductCount = $filter->products()->where('sub_category_id',$id)->count();

            $subFiltersWithCounts = $filter->subFilters->map(function ($subFilter) use($id)  {
                return [
                    'id' => $subFilter->id,
                   'name_ru' => $subFilter->name_ru,
                    'name_arm' => $subFilter->name_arm,
                    'name_en' => $subFilter->name_en,
                    'product_count' => $subFilter->products()->where('sub_category_id',$id)->count(),
                ];
            });

            return [
                'id' => $filter->id,
                'name_ru' => $filter->name_ru,
                'name_arm' => $filter->name_arm,
                'name_en' => $filter->name_en,
                'type' => $filter->type,
                'product_count' => $filterProductCount,
                'sub_filters' => $subFiltersWithCounts,
            ];
        });
    
        $products = Product::where('sub_category_id',$id)->where('count','!=',0);
        $products = $this->filterData($products,$data);
        $products= $products->paginate(20);
                $prices = Product::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();

            return Inertia::render('Catalog/Index', [
                'categories' =>Category::with('children.children')->whereNull('parent_id')->get(),
                'textBanners' =>$banners,
                'category' =>$category,
                'products' =>$products,
                'filtersWithCounts' =>$filtersWithCounts,
                'prices'=>$prices,
            ]);
    }

   

    public function getBySubSubCategory($id)
    {
        $data =Request::all();

        $banners = Banner::where('is_active',1)->orderBy('position','asc')->get()   ;         
        $subSubCategory =Category::where('id',$id)->first();
        $subCategory =Category::where('parent_id',$subSubCategory->parent_id)->first();
        $category = Category::with(['filters.subFilters'])->findOrFail($subCategory->parent_id);
        $filtersWithCounts = $category->filters->map(function ($filter) use($id){
            $filterProductCount = $filter->products()->where('sub_sub_category_id',$id)->count();

            $subFiltersWithCounts = $filter->subFilters->map(function ($subFilter)use($id) {
                return [
                    'id' => $subFilter->id,
                   'name_ru' => $subFilter->name_ru,
                    'name_arm' => $subFilter->name_arm,
                    'name_en' => $subFilter->name_en,
                    'product_count' => $subFilter->products()->where('sub_sub_category_id',$id)->count(),
                ];
            });

            return [
                'id' => $filter->id,
                'name_ru' => $filter->name_ru,
                'name_arm' => $filter->name_arm,
                'name_en' => $filter->name_en,
                'type' => $filter->type,
                'product_count' => $filterProductCount,
                'sub_filters' => $subFiltersWithCounts,
            ];
        });
    
        $products = Product::where('sub_sub_category_id',$id)->where('count','!=',0);
        $products = $this->filterData($products,$data);
        $products= $products->paginate(20);
        $prices = Product::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();

            return Inertia::render('Catalog/Index', [
                'categories' =>Category::with('children.children')->whereNull('parent_id')->get(),
                'textBanners' =>$banners,
                'category' =>$category,
                'products' =>$products,
                'filtersWithCounts' =>$filtersWithCounts,
                'prices'=>$prices,
            ]);
   

   
    }
    public function product($id)  {
        $product = Product::where('id',$id)->where('count','!=',0)->with('images')->first();
        return Inertia::render('Home/Index', [
            'product' =>$product,
        ]);
    }

    public function products()  {
        $data =Request::all();

        $product = Product::with('images');
        if (isset($data['is_new'])) {
            $product = $product->where('is_new',1);
        }
        if (isset($data['is_bestseller'])) {
            $product = $product->where('is_bestseller',1);
        }
        if (isset($data['discount'])) {
            $product = $product->where('discount','>',0);
        }
        $product = $product->paginate(10);
        
        return [
            'products' =>$product,
        ];
    }

    public function test()  {
        $data=[];
        \Mail::send('welcome', $data, function($message)use($data) {
            $message->to('tarongyulumyan@gmail.com', 'Welcome')
                    ->subject('Welcome');
            $message->from('armeniayvesrocher@gmail.com', 'Welcome');
       });

    }

    public function randomCategories()  {
        $categories =Category::where('parent_id',null)->inRandomOrder()->limit(4)->get();
        
        return [
             'categories' =>$categories,
        ];
    }

}
