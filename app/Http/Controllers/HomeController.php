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
use App\Models\Filter;

class HomeController extends Controller
{
    public function index(): Response
    {

            $banners = Banner::where('is_active',1)->orderby('position','asc')->get();  
            $textBanners = $banners->filter(function ($banner) {
                return $banner->type == 1; // type == 1 для текстовых баннеров
            });

            $imageBanners = $banners->filter(function ($banner) {
                return $banner->type != 1; // Все остальные баннеры для изображений
            });
            
        return Inertia::render('Home/Index', [
            'categories' =>Category::with('children.children')->whereNull('parent_id')->get(),
            'textBanners' =>$banners,
            'imageBanners' =>$banners,
        ]);
    }
    public function search(Request $request)
    {
        $data =Request::all();
        $search =  $data['search']; // или 'search'
        $banners = Banner::where('is_active',1)->orderBy('position','asc')->get()   ;   
        $products = Product::query()
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'LIKE', "%{$search}%")
                  ->orWhere('name_ru', 'LIKE', "%{$search}%")
                  ->orWhere('name_arm', 'LIKE', "%{$search}%")
                  ->orWhere('description_arm', 'LIKE', "%{$search}%")
                  ->orWhere('description_en', 'LIKE', "%{$search}%")
                  ->orWhere('description_ru', 'LIKE', "%{$search}%");
            });
        })
        ->with(['filters', 'subFilters'])
        ->get();
    
    $productIds = $products->pluck('id');
    
    // Получаем уникальные фильтры
    $filters = Filter::whereHas('products', function ($q) use ($productIds) {
        $q->whereIn('products.id', $productIds);
    })->with(['subFilters' => function ($query) use ($productIds) {
        $query->whereHas('products', function ($q) use ($productIds) {
            $q->whereIn('products.id', $productIds);
        });
    }])->paginate(20);
    
    // Сборка структуры с product_count
    $filtersWithCounts = $filters->map(function ($filter) use ($productIds) {
        $subFilters = $filter->subFilters->map(function ($subFilter) use ($productIds) {
            return [
                'id' => $subFilter->id,
                'name_ru' => $subFilter->name_ru,
                'name_arm' => $subFilter->name_arm,
                'name_en' => $subFilter->name_en,
                'product_count' => $subFilter->products()->whereIn('products.id', $productIds)->count(),
            ];
        });
    
        return [
            'id' => $filter->id,
            'name_ru' => $filter->name_ru,
            'name_arm' => $filter->name_arm,
            'name_en' => $filter->name_en,
            'type' => $filter->type,
            'product_count' => $filter->products()->whereIn('products.id', $productIds)->count(),
            'sub_filters' => $subFilters,
        ];
    });
    
            return Inertia::render('Catalog/Index', [
                'categories' =>Category::with('children.children')->whereNull('parent_id')->get(),
                'textBanners' =>$banners,
                'category' =>[],
                'products' =>$products,
                'filtersWithCounts' =>$filtersWithCounts,
                'prices'=>['max_price'=>1111,'min_price'=>11],
            ]);
    }
   

   

   
   

   
}
