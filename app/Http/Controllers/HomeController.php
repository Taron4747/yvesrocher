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
        $search = $request->input('search'); // или 'search'
    
        $products = Product::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name_en', 'LIKE', "%{$search}%")
                      ->orWhere('name_ru', 'LIKE', "%{$search}%")
                      ->orWhere('name_arm', 'LIKE', "%{$search}%");
                });
            })
            ->paginate(20);
    
        return response()->json($products);
    }
   

   

   
   

   
}
