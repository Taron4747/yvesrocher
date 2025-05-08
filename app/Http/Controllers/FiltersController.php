<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use App\Models\SubFilter;
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
                SubFilter::create($filterdata);
            }
        }
        return Redirect::route('filter')->with('success', 'Filter created.');
    }

    public function edit(Filter $filter): Response
    {
        // dd( $filter->load('subFilters'));
        return Inertia::render('Filters/Edit', [
            'filter' => $filter->load('subFilters'),
           
        ]);
    }

    public function update(Request $request, Filter $filter): RedirectResponse
    {
        $request = Request::instance();
        $request->validate([
            'name_en' => ['required', 'max:50'],
            'name_arm' => ['required', 'max:50'],
            'name_ru' => ['required', 'max:50'],
        ]);
    
        $filter->update([
            'name_en' => $request->name_en,
            'name_arm' => $request->name_arm,
            'name_ru' => $request->name_ru,
            'filterable' => $request->filterable,
        ]);
    
        $existingIds = collect($request->customData)->pluck('id')->filter()->toArray();

        // Удаляем те значения, которых больше нет
        $filter->values()->whereNotIn('id', $existingIds)->delete();
        
        // Обновляем и добавляем
        foreach ($request->customData as $item) {
            if (!empty($item['id'])) {
                // Обновить существующее значение
                FilterValue::where('id', $item['id'])->update([
                    'name_en' => $item['name_en'],
                    'name_arm' => $item['name_arm'],
                    'name_ru' => $item['name_ru'],
                ]);
            } else {
                // Добавить новое значение
                FilterValue::create([
                    'name_en' => $item['name_en'],
                    'name_arm' => $item['name_arm'],
                    'name_ru' => $item['name_ru'],
                    'filter_id' => $filter->id,
                ]);
            }
        }
        
    
        return redirect()->route('filter')->with('success', 'Filter updated.');
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
