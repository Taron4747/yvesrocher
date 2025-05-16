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
            return Inertia::render('Home/Index', [
                'category' =>$category,
                'products' =>$products,
                'filtersWithCounts' =>$filtersWithCounts,
            ]);
    }
    public function getBySubCategory($id)
    {
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
            return Inertia::render('Home/Index', [
                'category' =>$category,
                'products' =>$products,
                'filtersWithCounts' =>$filtersWithCounts,
            ]);
    }

   

    public function getBySubSubCategory($id)
    {
        $subSubCategory =Category::where('id',$id)->first();
        $subCategory =Category::where('parent_id',$id)->first();
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
            return Inertia::render('Home/Index', [
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

}
