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
use App\Models\Filter;
use App\Models\Proposal;

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
        $data =Request::all();
        $banners = Banner::where('is_active',1)->orderBy('position','asc')->get()   ;         
        $category = Category::with(['filters.subFilters','children'])->findOrFail($id);
        $filtersWithCounts =$this->filtersWithCounts($id,$data,'category_id',$category);
        $products = Product::where('category_id',$id)->where('count','!=',0)->with('images');
        $products = $this->filterData($products,$data);
        $products = $this->sortData($products,$data);
        $minPrice = (clone $products)->min('price');
        $maxPrice = (clone $products)->max('price');
        $new = (clone $products)->where('is_new',1)->count();
        $bestseller = (clone $products)->where('is_bestseller',1)->count();
        $discount = (clone $products)->where('discount','>',0)->count();
        $products= $products->paginate(20);
        $products->getCollection()->transform(function ($product) {
            if ($product->images->isEmpty() && $product->image) {
                $product->setRelation('images', collect([
                    (object)[
                        'id' => null,
                        'path' => $product->image, // предполагается, что это путь или URL
                    ]
                ]));
            }
            return $product;
        });
        $prices = Product::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();

            return Inertia::render('Catalog/Index', [
                'categories' =>Category::with('children.children')->whereNull('parent_id')->get(),
                'textBanners' =>$banners,
                'category' =>$category,
                'products' =>$products,
                'maxPrice' =>$maxPrice,
                'minPrice' =>$minPrice,
                'discount' =>$discount,
                'new' =>$new,
                'bestseller' =>$bestseller,
                'filtersWithCounts' =>$filtersWithCounts,
                'prices'=>$prices,
                'subCategory'=>[],
                'subSubCategory'=>[],
            ]);
    }
    private function sortData($query, $data)
{
    if (!isset($data['sorting'])) {
        return $query;
    }

    switch ($data['sorting']) {
        case 'name_asc':
            return $query->orderBy('name_' . app()->getLocale(), 'asc');

        case 'name_desc':
            return $query->orderBy('name_' . app()->getLocale(), 'desc');

        case 'price_asc':
            return $query->orderBy('price', 'asc');
        case 'price_desc':
            return $query->orderBy('price', 'desc');
        case 'discount_asc':
            return $query->orderBy('discount', 'asc');
        case 'discount_desc':
            return $query->orderBy('discount', 'desc');
        default:
            return $query;
    }
}
    private function filtersWithCounts($id,$data,$key,$category){
        // $category = Category::with(['filters.subFilters','children'])->findOrFail($id);

        $filtersWithCounts = $category->filters->map(function ($filter) use($id,$data,$key){
            $query = $filter->products()->where($key, $id);

            if (isset($data['new'])) {
                $query->where('is_new', 1);
            }

            if (isset($data['bestseller'])) {
                $query->where('is_bestseller', 1);
            }

            if (isset($data['discount'])) {
                $query->where('discount', '>', 0);
            }
            $filterProductCount = $query->count();
            $subFiltersWithCounts = $filter->subFilters->map(function ($subFilter)use($id,$data,$key) {

                $query =$subFilter->products()->where($key,$id);

                if (isset($data['new'])) {
                    $query->where('is_new', 1);
                }
    
                if (isset($data['bestseller'])) {
                    $query->where('is_bestseller', 1);
                }
    
                if (isset($data['discount'])) {
                    $query->where('discount', '>', 0);
                }
                $product_count = $query->count();

                return [
                    'id' => $subFilter->id,
                    'name_ru' => $subFilter->name_ru,
                    'name_arm' => $subFilter->name_arm,
                    'name_en' => $subFilter->name_en,
                    'product_count' => $product_count,
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
        return $filtersWithCounts;
    }
    private function filterData($products,$data){
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
        $filtersWithCounts =$this->filtersWithCounts($id,$data,'sub_category_id',$category);    
        $products = Product::where('sub_category_id',$id)->where('count','!=',0)->with('images');
        $products = $this->filterData($products,$data);
        $products = $this->sortData($products,$data);

        $minPrice = (clone $products)->min('price');
        $maxPrice = (clone $products)->max('price');
        $new = (clone $products)->where('is_new',1)->count();
        $bestseller = (clone $products)->where('is_bestseller',1)->count();
        $discount = (clone $products)->where('discount','>',0)->count();
        $products= $products->paginate(20);
        $products->getCollection()->transform(function ($product) {
            if ($product->images->isEmpty() && $product->image) {
                $product->setRelation('images', collect([
                    (object)[
                        'id' => null,
                        'path' => $product->image, // предполагается, что это путь или URL
                    ]
                ]));
            }
            return $product;
        });
                $prices = Product::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();
            return Inertia::render('Catalog/Index', [
                'categories' =>Category::with('children.children')->whereNull('parent_id')->get(),
                'textBanners' =>$banners,
                'category' =>$category,
                'products' =>$products,
                'maxPrice' =>$maxPrice,
                'minPrice' =>$minPrice,
                'discount' =>$discount,
                'new' =>$new,
                'bestseller' =>$bestseller,
                'filtersWithCounts' =>$filtersWithCounts,
                'prices'=>$prices,
                'subCategory'=>$subCategory,
                'subSubCategory'=>[],

            ]);
    }

   

    public function getBySubSubCategory($id)
    {
        $data =Request::all();

        $banners = Banner::where('is_active',1)->orderBy('position','asc')->get()   ;         
        $subSubCategory =Category::where('id',$id)->first();
        $subCategory =Category::where('id',$subSubCategory->parent_id)->first();

        $category = Category::with(['filters.subFilters'])->findOrFail($subCategory->parent_id);
        $filtersWithCounts =$this->filtersWithCounts($id,$data,'sub_sub_category_id',$category);
        $products = Product::where('sub_sub_category_id',$id)->where('count','!=',0)->with('images');
        $products = $this->filterData($products,$data);
        $products = $this->sortData($products,$data);

        $minPrice = (clone $products)->min('price');
        $maxPrice = (clone $products)->max('price');
        $new = (clone $products)->where('is_new',1)->count();
        $bestseller = (clone $products)->where('is_bestseller',1)->count();
        $discount = (clone $products)->where('discount','>',0)->count();
        $products= $products->paginate(20);
        $products->getCollection()->transform(function ($product) {
            if ($product->images->isEmpty() && $product->image) {
                $product->setRelation('images', collect([
                    (object)[
                        'id' => null,
                        'path' => $product->image, // предполагается, что это путь или URL
                    ]
                ]));
            }
            return $product;
        });
        $prices = Product::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();
            return Inertia::render('Catalog/Index', [
                'categories' =>Category::with('children.children')->whereNull('parent_id')->get(),
                'textBanners' =>$banners,
                'category' =>$category,
                'products' =>$products,
                'filtersWithCounts' =>$filtersWithCounts,
                'maxPrice' =>$maxPrice,
                'minPrice' =>$minPrice,
                'discount' =>$discount,
                'new' =>$new,
                'bestseller' =>$bestseller,
                'prices'=>$prices,
                'subSubCategory'=>$subSubCategory,
                'subCategory'=>$subCategory,
            ]);
   

   
    }
    public function product($id)  {
        $product = Product::where('id',$id)->where('count','!=',0)->with('images','category','subCategory','subSubCategory')->first();
        // dd( $product);
        return Inertia::render('SingleProduct/Index', [
            'product' =>$product,
            'categories' =>Category::with('children.children')->whereNull('parent_id')->get(),
            'banners' =>Banner::where('is_active',1)->orderBy('position','asc')->get()
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
    public function promotions()
    {
        $data =Request::all();
        $type = $data['type'] ?? null;

        $banners = Banner::where('is_active',1)->orderBy('position','asc')->get()   ;         
        // $category = Category::with(['filters.subFilters','children'])->findOrFail($id);
        // $filtersWithCounts =$this->filtersWithCounts($id,$data,'category_id',$category);
        $products = Product::where('count','!=',0)->with('images');
        // $products = $this->filterData($products,$data);
        $products = $this->sortData($products,$data);
        $minPrice = (clone $products)->min('price');
        $maxPrice = (clone $products)->max('price');
        $new = (clone $products)->where('is_new',1)->count();
        $bestseller = (clone $products)->where('is_bestseller',1)->count();
        $discount = (clone $products)->where('discount','>',0)->count();
        if ($type === 'new') {
            $products->where('is_new', 1);
        } elseif ($type === 'bestseller') {
            $products->where('is_bestseller', 1);
        } elseif ($type === 'ecco') {
            $products->where('is_ecco', 1);
        } elseif ($type === 'discount') {
            $products->where('discount', '>', 0);
        }
        $productIds = $products->pluck('id');
        $filters = Filter::whereHas('products', function ($q) use ($productIds) {
            $q->whereIn('products.id', $productIds);
        })->with(['subFilters' => function ($query) use ($productIds) {
            $query->whereHas('products', function ($q) use ($productIds) {
                $q->whereIn('products.id', $productIds);
            });
        }])->get();
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
        if (isset($data['filters'])) {
            $subFilterIds = Arr::flatten($data['filters']);
            $products =$products->whereHas('subFilters', function ($query) use ($subFilterIds) {
                $query->whereIn('sub_filter_id', $subFilterIds);
            });
        }
        $products= $products->paginate(20);
        $products->getCollection()->transform(function ($product) {
            if ($product->images->isEmpty() && $product->image) {
                $product->setRelation('images', collect([
                    (object)[
                        'id' => null,
                        'path' => $product->image, // предполагается, что это путь или URL
                    ]
                ]));
            }
            return $product;
        });
        $prices = Product::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();
        $typeMapping = [
            'new' => 'Новинки',
            'bestseller' => 'Бестселлеры',
            'discount' => 'Скидки',
            'ecco' => 'Экоформаты',
        ];
    
        $proposals = collect();
        if (isset($typeMapping[$type])) {
            $proposals = Proposal::where('type', $typeMapping[$type])->first();
        }
        $category = [
            'name_en'=>$proposals->description_en,
            'name_ru'=>$proposals->description_ru,
            'name_arm'=>$proposals->description_arm,
      
            'description_arm'=>$proposals->proposition_arm,
            'description_ru'=>$proposals->proposition_ru,
            'description_en'=>$proposals->proposition_en,
            'second_image'=>$proposals->image,
        ];
            return Inertia::render('Catalog/Index', [
                'categories' =>Category::with('children.children')->whereNull('parent_id')->get(),
                'textBanners' =>$banners,
                'category' =>$category,
                'products' =>$products,
                'maxPrice' =>$maxPrice,
                'minPrice' =>$minPrice,
                'discount' =>$discount,
                'new' =>$new,
                'bestseller' =>$bestseller,
                'filtersWithCounts' =>$filtersWithCounts,
                'prices'=>$prices,
                'subCategory'=>[],
                'subSubCategory'=>[],
            ]);
    }
}
