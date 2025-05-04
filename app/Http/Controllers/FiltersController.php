<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use App\Models\FilterValue;
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
            'filters_data' =>Filter::
                filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($filter) => [
                    'id' => $filter->id,
                    'name_arm' => $filter->name_arm,
                    'name_en' => $filter->name_en,
                    'name_ru' => $filter->name_ru,
                    'image' => $filter->image,
                    
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Filters/Create', [
          
        ]);
    }

    public function store(): RedirectResponse
    {
        Request::validate([
            'name_en' => ['required', 'max:50'],
            'name_arm' => ['required', 'max:50'],
            'name_ru' => ['required', 'max:50'],
    
        ]);
       

        $data =Request::all();
        $insertdata = [
            'name_en' => $data['name_en'],
            'name_arm' => $data['name_arm'],
            'name_ru' => $data['name_ru'],
            'filterable' => $data['filterable'],

        ];
        $filter = Filter::create($insertdata) ;
        if ($data['filterable'] ==true) {
            foreach ($data['customData'] as $key => $customData) {
                $filterdata =[
                    'name_en' => $customData['name_en'],
                    'name_arm' => $customData['name_arm'],
                    'name_ru' => $customData['name_ru'],
                    'filter_id'=>$filter->id,
                ];
                FilterValue::create($filterdata);
            }
        }
        return Redirect::route('filter')->with('success', 'Filter created.');
    }

    public function edit(Filter $filter): Response
    {
        return Inertia::render('Categories/Edit', [
            'filter' => [
                'id' => $filter->id,
                'name_arm' => $filter->name_arm,
                'name_ru' => $filter->name_ru,
                'name_en' => $filter->name_en,
                'filterable' => $filter->filterable,
              
                'deleted_at' => $filter->deleted_at,
            ],
           
        ]);
    }

    public function update(Category $filter): RedirectResponse
    {
        $filter->update(
            Request::validate([
                'name_arm' => ['required', 'max:50'],
                'name_ru' => ['required', 'max:50'],
                'name_en' => ['required', 'max:50'],
            ])
        );

        return Redirect::back()->with('success', 'Category updated.');
    }

    public function destroy(Category $filter): RedirectResponse
    {
        $filter->delete();

        return Redirect::back()->with('success', 'Category deleted.');
    }

    public function restore(Category $filter): RedirectResponse
    {
        $filter->restore();

        return Redirect::back()->with('success', 'Category restored.');
    }
}
