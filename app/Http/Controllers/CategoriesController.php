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


        $category = Category::with(['filters.values'])->find(16);
// dd( $category->filters);
        $filtersWithCounts = $category->filters->map(function ($filter) use ($category) {
            $valuesWithCount = $filter->values->map(function ($value) use ($category) {
                $productCount = Product::where('category_id', $category->id)
                    ->whereHas('filterValues', function ($q) use ($value) {
                        $q->where('filter_value_id', $value->id);
                    })
                    ->count();
        
                return [
                    'id' => $value->id,
                    'name' => $value->name_arm,
                    'product_count' => $productCount,
                ];
            });
        
            return [
                'id' => $filter->id,
                'name' => $filter->name_arm,
                'values' => $valuesWithCount,
            ];
        });

dd( $filtersWithCounts);
        $filters = Filter::with('values')->get();
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
            'image' => ['required', 'image'],
        
        ]);
        $path = Request::file('image')->store('images', 's3', 'public');
        $url = \Storage::disk('s3')->url($path);

      

        $data =Request::all();
        $insertdata = [
            'name_en' => $data['name_en'],
            'name_arm' => $data['name_arm'],
            'name_ru' => $data['name_ru'],
            'image' => $url,

        ];
       $category = Category::create($insertdata) ;
        if (isset($data['filters'])) {
            foreach ($data['filters'] as $key => $filters) {
                if ( $filters['type']==1) {
                    foreach ($filters['values'] as $key => $filter) {

                        if ($filter['type']==1) {
                            $filterData =null;
                            $filterData =[
                                'category_id'=>$category->id,
                                'filter_value_id'=>$filter['id'],
                                'filter_id'=>$filters['id'],
                            ];
                            FilterCategory::create( $filterData);
                        }
                    }
                }
            }
        }
        if (isset($data['button_filters'])) {
            foreach ($data['button_filters'] as $key => $button_filter) {
                $filterData =null;
                            $filterData =[
                                'category_id'=>$category->id,
                                'filter_id'=>$button_filter['id'],
                            ];
                            FilterCategory::create( $filterData);
            }
        }
        return Redirect::route('categories')->with('success', 'Category created.');
    }

    public function edit(Category $category): Response
    {
        return Inertia::render('Categories/Edit', [
            'category' => [
                'id' => $category->id,
                'name_arm' => $category->name_arm,
                'name_ru' => $category->name_ru,
                'name_en' => $category->name_en,
              
                'deleted_at' => $category->deleted_at,
            ],
            'organizations' => Auth::user()->account->organizations()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
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
       
        return Inertia::render('SubCategories/Edit', [
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
}
