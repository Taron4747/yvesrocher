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

class CatalogController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home/Index', [
            'categories' =>Category::with('children.children')->whereNull('parent_id')->get(),
            'banners' =>Banner::all()
        ]);
    }
    public function getByCategory($id)
    {
        $banners = Banner::all()   ;         
            $textBanners = $banners->filter(function ($banner) {
                return $banner->type == 1; // type == 1 для текстовых баннеров
            });
        $category = Category::with(['filters.subFilters'])->findOrFail($id);
        $filtersWithCounts = $category->filters->map(function ($filter) {
            $filterProductCount = $filter->products()->count();

            $subFiltersWithCounts = $filter->subFilters->map(function ($subFilter) {
                return [
                    'id' => $subFilter->id,
                    'name' => $subFilter->name,
                    'product_count' => $subFilter->products()->count(),
                ];
            });
            return [
                'id' => $filter->id,
                'name' => $filter->name,
                'type' => $filter->type,
                'product_count' => $filterProductCount,
                'sub_filters' => $subFiltersWithCounts,
            ];
        });
    
        $products = Product::where('category_id',$id)->where('count','!=',0)
            ->paginate(20);
            return Inertia::render('Catalog/Index', [
                'categories' =>Category::with('children.children')->whereNull('parent_id')->get(),
                'textBanners' =>$textBanners,
                'category' =>$category,
                'products' =>$products,
                'filtersWithCounts' =>$filtersWithCounts,
            ]);
    }
    public function getBySubCategory($id)
    {
        $banners = Banner::all()   ;         
            $textBanners = $banners->filter(function ($banner) {
                return $banner->type == 1; // type == 1 для текстовых баннеров
            });
        $subCategory =Category::where('id',$id)->first();
        $category = Category::with(['filters.subFilters'])->findOrFail($subCategory->parent_id);
        $filtersWithCounts = $category->filters->map(function ($filter) {
            $filterProductCount = $filter->products()->count();

            $subFiltersWithCounts = $filter->subFilters->map(function ($subFilter) {
                return [
                    'id' => $subFilter->id,
                    'name' => $subFilter->name,
                    'product_count' => $subFilter->products()->count(),
                ];
            });

            return [
                'id' => $filter->id,
                'name' => $filter->name,
                'type' => $filter->type,
                'product_count' => $filterProductCount,
                'sub_filters' => $subFiltersWithCounts,
            ];
        });
    
        $products = Product::where('sub_category_id',$id)->where('count','!=',0)
            ->paginate(20);
            return Inertia::render('Catalog/Index', [
                'categories' =>Category::with('children.children')->whereNull('parent_id')->get(),
                'textBanners' =>$textBanners,
                'category' =>$category,
                'products' =>$products,
                'filtersWithCounts' =>$filtersWithCounts,
            ]);
    }

   

    public function getBySubSubCategory($id)
    {
        $banners = Banner::all()   ;         
            $textBanners = $banners->filter(function ($banner) {
                return $banner->type == 1; // type == 1 для текстовых баннеров
            });
        $subSubCategory =Category::where('id',$id)->first();
        $subCategory =Category::where('parent_id',$subSubCategory->parent_id)->first();
        $category = Category::with(['filters.subFilters'])->findOrFail($subCategory->parent_id);
        $filtersWithCounts = $category->filters->map(function ($filter) {
            $filterProductCount = $filter->products()->count();

            $subFiltersWithCounts = $filter->subFilters->map(function ($subFilter) {
                return [
                    'id' => $subFilter->id,
                    'name' => $subFilter->name,
                    'product_count' => $subFilter->products()->count(),
                ];
            });

            return [
                'id' => $filter->id,
                'name' => $filter->name,
                'type' => $filter->type,
                'product_count' => $filterProductCount,
                'sub_filters' => $subFiltersWithCounts,
            ];
        });
    
        $products = Product::where('sub_sub_category_id',$id)->where('count','!=',0)
            ->paginate(20);
            return Inertia::render('Catalog/Index', [
                'categories' =>Category::with('children.children')->whereNull('parent_id')->get(),
                'textBanners' =>$textBanners,
                'category' =>$category,
                'products' =>$products,
                'filtersWithCounts' =>$filtersWithCounts,
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
        $product = $product->paginate(20);
        
        return [
            'products' =>$product,
        ];
    }

}
