<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\URL;

class FiltersController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Filters/Index', [
            'filters' => Request::all('search', 'trashed'),
            'filters_data' =>Category::
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
        return Inertia::render('Filters/Create', [
            'organizations' => Auth::user()->account
                ->organizations()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function store(): RedirectResponse
    {
//        \Storage::disk('s3')->put('test.txt', 'Hello, S3!');
dd(Request::all());
Request::validate([
    'name_en' => ['required', 'max:50'],
    'name_arm' => ['required', 'max:50'],
    'name_ru' => ['required', 'max:50'],
    'image' => ['required', 'image'],
   
]);
        $path = Request::file('image')->store('images', 's3');
        $url = \Storage::disk('s3')->url($path);

        $data =Request::all();
        $insertdata = [
            'name_en' => $data['name_en'],
            'name_arm' => $data['name_arm'],
            'name_ru' => $data['name_ru'],
            'image' => $url,

        ];
// // Получение URL
        // dd($url,$path);
        Category::create($insertdata) ;

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
}
