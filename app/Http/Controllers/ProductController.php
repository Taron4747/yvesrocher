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
            'butonFilters'=> $filters->where('filterable',false)->values()->toArray(),
        ]);
    }

    public function store(): RedirectResponse
    {

        $data =Request::all();

        $filters =isset($data['filters']) ?$data['filters'] :null;
        $button_filters =isset($data['button_filters']) ?$data['button_filters'] :null;
        unset($data['button_filters']);
        unset($data['filters']);
        unset($data['images']);
        $validator = Validator::make(Request::all(), [
                'name_arm' => ['required', 'max:50'],
                'name_ru' => ['required', 'max:50'],
                'name_en' => ['required', 'max:50'],
                'size' => ['required', 'max:50'],
                'description_arm' => ['required'],
                'description_ru' => ['required'],
                'description_en' => ['required'],
                'composition_ru' => ['required'],
                'composition_arm' => ['required'],
                'composition_en' => ['required'],
                'image' => ['required'],
                'price' => ['required','integer'],
        ]);
        $validator->validate();
        $path = Request::file('image')[0]->store('images', 's3', 'public');
        $image = Storage::disk('s3')->url($path);
        $data['image'] =$image;
        
        $product = Product::create($data);
        if (Request::file('images')) {

            foreach (Request::file('images') as $key => $image) {
                $path = Request::file('images')[ $key]->store('images', 's3', 'public');
                $image = Storage::disk('s3')->url($path);
                ProductImage::create(['product_id'=>$product->id,'path'=> $image]);
            }
        }
        if (isset($filters)) {
            
            foreach ($filters as $key => $filters) {
                if ( $filters['type']==1) {
                $product->filters()->attach($filters['id']);

                    foreach ($filters['sub_filters'] as $key => $filter) {
                        if ( $filter['type']==1) {
                            $product->subFilters()->attach($filter['id']);
                       
                        }
                    }
                }
            }
        }
        if (isset($button_filters)) {
            foreach ($button_filters as $key => $button_filter) {
                $product->filters()->attach($button_filter['id']);
            }
        }
        return Redirect::route('admin/product')->with('success', 'Contact created.');
    }

    public function edit(Contact $contact): Response
    {
        return Inertia::render('Products/Edit', [
            'contact' => [
                'id' => $contact->id,
                'first_name' => $contact->first_name,
                'last_name' => $contact->last_name,
                'organization_id' => $contact->organization_id,
                'email' => $contact->email,
                'phone' => $contact->phone,
                'address' => $contact->address,
                'city' => $contact->city,
                'region' => $contact->region,
                'country' => $contact->country,
                'postal_code' => $contact->postal_code,
                'deleted_at' => $contact->deleted_at,
            ],
            'organizations' => Auth::user()->account->organizations()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function update(Contact $contact): RedirectResponse
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

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return Redirect::back()->with('success', 'Contact deleted.');
    }

    public function restore(Contact $contact): RedirectResponse
    {
        $contact->restore();

        return Redirect::back()->with('success', 'Contact restored.');
    }
}
