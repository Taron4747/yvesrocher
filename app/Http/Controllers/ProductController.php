<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Filter;
use App\Models\ProductImage;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{

//     public function index(Request $request)
// {
//     $query = Product::with('attributeValues.attribute');

//     if ($filters = $request->input('filters')) {
//         foreach ($filters as $attribute => $values) {
//             $query->whereHas('attributeValues.attribute', function ($q) use ($attribute, $values) {
//                 $q->where('name', $attribute)
//                   ->whereIn('attribute_values.value', $values);
//             });
//         }
//     }

//     return Inertia::render('Products/Index', [
//         'products' => $query->paginate(12),
//         'filters' => Attribute::with('values')->where('filterable', true)->get(),
//         'selected' => $request->filters ?? [],
//     ]);
// }
    public function index(): Response
    {
        return Inertia::render('Products/Index', [
            'filters' => Request::all('search', 'trashed'),
            'products' => Product::
                orderBy('id')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($organization) => [
                    'id' => $organization->id,
                    'name_en' => $organization->name_en,
                    'name_arm' => $organization->name_arm,
                    'name_ru' => $organization->name_ru,
                    'price' => $organization->price,
                    'size' => $organization->size,
                    'discount' => $organization->discount,
                    'count' => $organization->count,
                ]),
        ]);
    }




    public function create(): Response
    {
        $filters = Filter::with('subFilters')->get();
      
        return Inertia::render('Products/Create', [
            'categories' =>Category::with('children.children')->get()->toArray(),
            'filters'=> $filters->where('filterable',true)->values()->toArray(),
        ]);
    }

    public function store(): RedirectResponse
    {

        $data =Request::all();

        $filters =isset($data['filters']) ?$data['filters'] :null;
        unset($data['filters']);
        unset($data['images']);
        $validator = Validator::make(Request::all(), [
                'name_arm' => ['required', 'max:50'],
                'name_ru' => ['required', 'max:50'],
                'name_en' => ['required', 'max:50'],
                'size' => ['required', 'max:5000'],
                'description_arm' => ['required'],
                'description_ru' => ['required'],
                'description_en' => ['required'],
                'composition_ru' => ['required'],
                'composition_arm' => ['required'],
                'composition_en' => ['required'],
                // 'product_code' => ['required|unique:products'],
                'product_code' => ['required', 'unique:products'], 

                'image' => ['required'],
                'price' => ['required','integer'],
        ]);
        $validator->validate();
        $path = Request::file('image')[0]->store('images', 's3', 'public');
        $image = Storage::disk('s3')->url($path);
        $data['image'] =$image;
        $data['is_new'] =$data['is_new']===null?0:$data['is_new'];
        $data['is_bestseller'] =$data['is_bestseller']===null?0:$data['is_bestseller'];
        $product = Product::create($data);
        if (Request::file('images')) {

            foreach (Request::file('images') as $key => $image) {
                $path = Request::file('images')[ $key]->store('images', 's3', 'public');
                $image = Storage::disk('s3')->url($path);
                ProductImage::create(['product_id'=>$product->id,'path'=> $image]);
            }
        }
        if (isset($filters)) {
            
            foreach ($filters as $key => $filter) {
                if ( $filter['type']==1) {
                $product->filters()->attach($filter['id']);
                    if (isset($filter['sub_filters'])) {
                        foreach ($filter['sub_filters'] as $key => $sub_filter) {
                            if ( $sub_filter['type']==1) {
                                $product->subFilters()->attach($sub_filter['id']);
                           
                            }
                        }
                    }
                   
                }
            }
        }
       
        return Redirect::route('product')->with('success', 'Contact created.');
    }

    public function edit(Product $product): Response
    {
        $product->load('filters','subFilters');
        $category =Category::where('id', $product->category_id)->first();
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

      
        $filters = Filter::with('subFilters')->get();
      
            
        return Inertia::render('Products/Edit', [
            'product' => [
                'id' => $product->id,
                'name_arm' => $product->name_arm,
                'name_ru' => $product->name_ru,
                'name_en' => $product->name_en,
                'size' => $product->size,
                'description_arm' => $product->description_arm,
                'description_ru' => $product->description_ru,
                'description_en' => $product->description_en,
                'composition_ru' => $product->composition_ru,
                'composition_arm' => $product->composition_arm,
                'composition_en' => $product->composition_en,
                'product_code' => $product->product_code,
                'image' => $product->image,
                'price' => $product->price,
                'category_id' => $product->category_id,
                'sub_category_id' => $product->sub_category_id,
                'filters' => $product->filters->where('filterefilterable',1)->values()->toArray(),
                'filters' => $product->subFilters->values()->toArray(),
                'butonFilters' => $product->filters->where('filterefilterable',0)->values()->toArray(),
                'sub_sub_category_id' => $product->sub_sub_category_id,
                'sub_sub_category_id' => $product->sub_sub_category_id,
            ],
            'categoryfilters'=>$categoryfilters->values()->toArray(),
            'categories' =>Category::with('children.children')->get()->toArray(),
            'filters'=> $filters->where('filterable',true)->values()->toArray(),
            'butonFilters'=> $filters->where('filterable',false)->values()->toArray(),
        ]);
    }

    public function update(Product $product): RedirectResponse
    {
        $contact->update(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'organization_id' => [
                    'nullable',
                    Rule::exists('organizations', 'id')->where(fn ($query) => $query->where('account_id', Auth::user()->account_id)),
                ],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::back()->with('success', 'Contact updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $contact->delete();

        return Redirect::back()->with('success', 'Contact deleted.');
    }

    public function restore(Product $product): RedirectResponse
    {
        $contact->restore();

        return Redirect::back()->with('success', 'Contact restored.');
    }


    function productCountChange() {
        $data =Request::all();
        Product::where('id',$data['id'])->update(['count'=>$data['count'],'price'=>$data['price']]);
        return response()->json(['success'=>true]);
        
    }
}
