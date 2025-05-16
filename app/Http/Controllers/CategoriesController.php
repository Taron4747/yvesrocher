<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Filter;
use App\Models\FilterCategory;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Categories/Index', [
            'filters' => Request::all('search', 'trashed'),
            'categories' =>Category::
                whereNull('parent_id')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($category) => [
                    'id' => $category->id,
                    'name_arm' => $category->name_arm,
                    'name_en' => $category->name_en,
                    'name_ru' => $category->name_ru,
                    'image' => $category->image,
                    
                ]),
        ]);
    }

    public function create(): Response
    {
        $filters = Filter::with('subFilters')->get();
        return Inertia::render('Categories/Create', [
           'filters'=> $filters->where('filterable',true)->values()->toArray(),
           'butonFilters'=> $filters->where('filterable',false)->values()->toArray(),
        ]);
    }

    public function store(): RedirectResponse
    {
        // dd(Request::all());
        Request::validate([
            'name_en' => ['required', 'max:50'],
            'name_arm' => ['required', 'max:50'],
            'name_ru' => ['required', 'max:50'],
            'image' => ['required'],
            'second_image' => ['required'],
        
        ]);
        $path = Request::file('image')[0]->store('images', 's3', 'public');
        $url = Storage::disk('s3')->url($path);

        $path = Request::file('second_image')[0]->store('images', 's3', 'public');
        $secondurl = Storage::disk('s3')->url($path);

        $data =Request::all();
        $insertdata = [
            'name_en' => $data['name_en'],
            'name_arm' => $data['name_arm'],
            'name_ru' => $data['name_ru'],
            'image' => $url,
            'second_image' => $secondurl,

        ];
       $category = Category::create($insertdata) ;
        if (isset($data['filters'])) {
            
            foreach ($data['filters'] as $key => $filters) {
                if ( $filters['type']==1) {
                $category->filters()->attach($filters['id']);

                    foreach ($filters['sub_filters'] as $key => $filter) {
                        if ( $filter['type']==1) {

                        $category->subFilters()->attach($filter['id']);
                       
                    }
                    }
                }
            }
        }
       
        return Redirect::route('categories')->with('success', 'Category created.');
    }

    public function edit(Category $category): Response
    {
        $categoryFilters =  $category->load('filters','subFilters');
        $filters = Filter::with('subFilters')->get();
        // $category = Category::with(['filters'])->findOrFail($categoryId);
        // Получаем ID всех субфильтров, привязанных к категории
        $categorySubFilterIds = $category->subFilters()->pluck('sub_filters.id');

        // Формируем данные для ответа
        $categoryfilters = $category->filters->where('filterable',1)->map(function ($filter) use ($categorySubFilterIds) {
            return [
                'id' => $filter->id,
                'name_arm' => $filter->name_arm,
                'name_ru' => $filter->name_ru,
                'name_en' => $filter->name_en,
                'filterable' => $filter->filterable,
                'sub_filters' => $filter->subFilters->whereIn('id', $categorySubFilterIds)->map(function ($subFilter) {
                    return [
                        'id' => $subFilter->id,
                        'name_arm' => $subFilter->name_arm,
                        'name_ru' => $subFilter->name_ru,
                        'name_en' => $subFilter->name_en,
                    ];
                }),
            ];
        });

        $categorySubFilters = $category->filters->where('filterable',0)->map(function ($filter) use ($categorySubFilterIds) {
            return [
                'id' => $filter->id,
                'name_arm' => $filter->name_arm,
                'name_ru' => $filter->name_ru,
                'name_en' => $filter->name_en,
                'filterable' => $filter->filterable,
               
            ];
        });

        return Inertia::render('Categories/Edit', [
            'category' => [
                'id' => $category->id,
                'name_arm' => $category->name_arm,
                'name_ru' => $category->name_ru,
                'name_en' => $category->name_en,
                'image' => $category->image,
                'second_image' => $category->second_image,
                // 'category_filters'=>  $categoryFilters->filters->where('filterable',true)->values()->toArray(),
                // 'category_buton_filters'=>  $categoryFilters->filters->where('filterable',false)->values()->toArray(),
                // 'category_sub_filters'=> $categoryFilters->subFilters->values()->toArray(),
                'category_filters'=>$categoryfilters,
                'category_sub_filters'=>$categorySubFilters,
                'filters'=> $filters->where('filterable',true)->values()->toArray(),
                'buton_filters'=> $filters->where('filterable',false)->values()->toArray(),
                'deleted_at' => $category->deleted_at,
            ],
          
        ]);
    }

    public function update(Category $category): RedirectResponse
    {
        $category->update(
            Request::validate([
                'name_arm' => ['required', 'max:50'],
                'name_ru' => ['required', 'max:50'],
                'name_en' => ['required', 'max:50'],
            ])
        );
        if (isset(Request::file('image')[0])) {
            $path = Request::file('image')[0]->store('images', 's3', 'public');
            $url = \Storage::disk('s3')->url($path);
            $category->update(['image'=>$url ]);
        }
        if (isset(Request::file('second_image')[0])) {
            $path = Request::file('second_image')[0]->store('images', 's3', 'public');
            $secondurl = \Storage::disk('s3')->url($path);
            $category->update(['second_image'=>$secondurl ]);

        }

      
        return Redirect::back()->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return Redirect::back()->with('success', 'Category deleted.');
    }

    public function restore(Category $category): RedirectResponse
    {
        $category->restore();

        return Redirect::back()->with('success', 'Category restored.');
    }


    public function indexSub(): Response
    {
        return Inertia::render('SubCategories/Index', [
            'filters' => Request::all('search', 'trashed'),
            'categories' =>Category::
                    whereIn('parent_id', function($query) {
                        $query->select('id')->from('categories')->whereNull('parent_id');
                    })
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($category) => [
                    'id' => $category->id,
                    'name_arm' => $category->name_arm,
                    'name_en' => $category->name_en,
                    'name_ru' => $category->name_ru,
                    'image' => $category->image ? URL::route('image', ['path' => $category->image, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                    
                ]),
        ]);
    }

    public function createSub(): Response
    {
        return Inertia::render('SubCategories/Create', [
            'categories' => Category::
            whereNull('parent_id')->get()
           
            
            
        ]);
    }

    public function storeSub(): RedirectResponse
    {
        Category::create(
            Request::validate([
                'name_en' => ['required', 'max:50'],
                'name_arm' => ['required', 'max:50'],
                'name_ru' => ['required', 'max:50'],
                'parent_id' => ['required'],
               
            ])
        );

        return Redirect::route('sub-categories')->with('success', 'Sub category created.');
    }

    public function editSub(Category $category): Response
    {
        return Inertia::render('SubCategories/Edit', [
            'category' => [
                'id' => $category->id,
                'name_arm' => $category->name_arm,
                'name_ru' => $category->name_ru,
                'name_en' => $category->name_en,
                'parent_id' => $category->parent_id,
              
                'deleted_at' => $category->deleted_at,
            ],
            'categories' => Category::
            whereNull('parent_id')->get()
        ]);
    }

    public function updateSub(Category $category): RedirectResponse
    {
        $category->update(
            Request::validate([
                'name_arm' => ['required', 'max:50'],
                'name_ru' => ['required', 'max:50'],
                'name_en' => ['required', 'max:50'],
            ])
        );

        return Redirect::back()->with('success', 'Sub Category updated.');
    }

    public function destroySub(Category $category): RedirectResponse
    {
        $category->delete();

        return Redirect::back()->with('success', 'Sub Category deleted.');
    }

    public function restoreSub(Category $category): RedirectResponse
    {
        $category->restore();
        return Redirect::back()->with('success', 'Sub Category restored.');

    }

    public function indexSubSub(): Response
    {
        return Inertia::render('SubSubCategories/Index', [
            'filters' => Request::all('search', 'trashed'),
            'categories' =>Category::
                whereIn('parent_id', function($query) {
                    $query->select('id')->from('categories')->whereIn('parent_id', function($q) {
                        $q->select('id')->from('categories')->whereNull('parent_id');
                    });
                })
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($category) => [
                    'id' => $category->id,
                    'name_arm' => $category->name_arm,
                    'name_en' => $category->name_en,
                    'name_ru' => $category->name_ru,
                    'image' => $category->image ? URL::route('image', ['path' => $category->image, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                    
                ]),
        ]);
    }

    public function createSubSub(): Response
    {
        return Inertia::render('SubSubCategories/Create', [
            'categories' => Category::
            whereIn('parent_id', function($query) {
                $query->select('id')->from('categories')->whereNull('parent_id');
            })->get()
           
            
            
        ]);
    }

    public function storeSubSub(): RedirectResponse
    {
        Category::create(
            Request::validate([
                'name_en' => ['required', 'max:50'],
                'name_arm' => ['required', 'max:50'],
                'name_ru' => ['required', 'max:50'],
                'parent_id' => ['required'],
               
            ])
        );

        return Redirect::route('sub-sub-categories')->with('success', 'Sub sub category created.');
    }

    public function editSubSub(Category $category): Response
    {
       
        return Inertia::render('SubSubCategories/Edit', [
            'category' => [
                'id' => $category->id,
                'name_arm' => $category->name_arm,
                'name_ru' => $category->name_ru,
                'name_en' => $category->name_en,
                'parent_id' => $category->parent_id,
              
                'deleted_at' => $category->deleted_at,
            ],
            'categories' => Category::whereIn('parent_id', function($query) {
                $query->select('id')->from('categories')->whereNull('parent_id');
            })->get()
        ]);
    }

    public function updateSubSub(Category $category): RedirectResponse
    {
        $category->update(
            Request::validate([
                'name_arm' => ['required', 'max:50'],
                'name_ru' => ['required', 'max:50'],
                'name_en' => ['required', 'max:50'],
            ])
        );

        return Redirect::back()->with('success', 'Sub Category updated.');
    }

    public function destroySubSub(Category $category): RedirectResponse
    {
        $category->delete();

        return Redirect::back()->with('success', 'Sub Category deleted.');
    }

    public function restoreSubSub(Category $category): RedirectResponse
    {
        $category->restore();
        return Redirect::back()->with('success', 'Sub Category restored.');

    }


    public function showFiltersWithCounts($categoryId)
    {
        // Получаем категорию с её фильтрами и субфильтрами
        $category = Category::with(['filters.subFilters'])->findOrFail($categoryId);

        // Список фильтров с количеством продуктов
        $filtersWithCounts = $category->filters->map(function ($filter) {
            // Подсчет количества продуктов для фильтра
            $filterProductCount = $filter->products()->count();

            // Субфильтры с количеством продуктов
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

        return view('filters.index', compact('filtersWithCounts', 'category'));
        //         SELECT f.id AS filter_id, f.name AS filter_name, COUNT(DISTINCT pf.product_id) AS product_count,
        //     sf.id AS sub_filter_id, sf.name AS sub_filter_name, COUNT(DISTINCT psf.product_id) AS sub_product_count
        // FROM filters f
        // LEFT JOIN product_filter pf ON f.id = pf.filter_id
        // LEFT JOIN sub_filters sf ON sf.filter_id = f.id
        // LEFT JOIN product_sub_filter psf ON sf.id = psf.sub_filter_id
        // WHERE f.id IN (
        //     SELECT filter_id FROM category_filter WHERE category_id = :category_id
        // )
        // GROUP BY f.id, sf.id;
    }

    function categoryFilters($id)  {
        $category =Category::where('id',$id)->first();
        $categoryFilters =  $category->load('filters','subFilters');
        $filters = Filter::with('subFilters')->get();
       
        $categorySubFilterIds = $category->subFilters()->pluck('sub_filters.id');

        $categoryfilters = $category->filters->where('filterable',1)->map(function ($filter) use ($categorySubFilterIds) {
            return [
                'id' => $filter->id,
                'name_arm' => $filter->name_arm,
                'name_ru' => $filter->name_ru,
                'name_en' => $filter->name_en,
                'filterable' => $filter->filterable,
                'sub_filters' => $filter->subFilters->whereIn('id', $categorySubFilterIds)->map(function ($subFilter) {
                    return [
                        'id' => $subFilter->id,
                        'name_arm' => $subFilter->name_arm,
                        'name_ru' => $subFilter->name_ru,
                        'name_en' => $subFilter->name_en,
                    ];
                })->toArray(),
            ];
        });

     
        return response()->json(['categoryfilters'=>$categoryfilters->values()->toArray()]);

    }

}
