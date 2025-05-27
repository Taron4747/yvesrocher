<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProposalsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Proposals/Index', [
            'filters' => Request::all('search', 'trashed'),
            'proposals' =>Proposal::
                filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($proposal) => [
                    'id' => $proposal->id,
                    'type' => $proposal->type,
                    'description_en' => $proposal->description_en,
                    'description_ru' => $proposal->description_ru,
                    'description_arm' => $proposal->description_arm,
                    'proposition_arm' => $proposal->proposition_arm,
                    'proposition_en' => $proposal->proposition_en,
                    'proposition_ru' => $proposal->proposition_ru,
                    'image' => $proposal->image,
                 
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Proposals/Create', [
           
        ]);
    }

    public function store(): RedirectResponse
    {
       
        $data =Request::all();
        // dd($data);
        $validator = Validator::make(Request::all(), [
            'type' => ['required'],
            'description_arm' => ['required'],
            'description_ru' => ['required'],
            'description_en' => ['required'],
            'proposition_ru' => ['required'],
            'proposition_en' => ['required'],
            'proposition_arm' => ['required'],
            'image' => ['required'],
        ]);
        $validator->validate();
        $path = Request::file('image')[0]->store('images', 's3', 'public');
        $image = Storage::disk('s3')->url($path);
        $data['image']= $image;
        Proposal::create($data);
        return Redirect::route('proposals')->with('success', 'Предложение бизнеса создано');
    }

    public function edit(Proposal $proposal): Response
    {
        return Inertia::render('Proposals/Edit', [
            'proposal' => [
                'id' => $proposal->id,
                'proposition_arm' => $proposal->proposition_arm,
                'proposition_en' => $proposal->proposition_en,
                'proposition_ru' => $proposal->id,
                'description_en' => $proposal->description_en,
                'description_ru' => $proposal->description_ru,
                'description_arm' => $proposal->description_arm,
                'type' => $proposal->type,
                'image' => $proposal->image,
             
            ],
           
        ]);
    }

    public function update(Proposal $proposal): RedirectResponse
    {
        $proposal->update(
            Request::validate([
                'type' => ['required'],
                'description_arm' => ['required'],
                'description_ru' => ['required'],
                'description_en' => ['required'],
                'proposition_ru' => ['required'],
                'proposition_en' => ['required'],
                'proposition_arm' => ['required'],
            ])
        );
        if (isset(Request::file('image_big')[0])) {
            $path = Request::file('image_big')[0]->store('images', 's3', 'public');
            $image_big = Storage::disk('s3')->url($path);
            $proposal->update(['image_big'=>$image_big]);
        }
        return Redirect::back()->with('success', 'Предложение бизнеса редактирована.');
    }

    public function destroy(Proposal $proposal): RedirectResponse
    {
        $proposal->delete();

        return Redirect::back()->with('success', 'Contact deleted.');
    }

    public function restore(Proposal $proposal): RedirectResponse
    {
        $proposal->restore();

        return Redirect::back()->with('success', 'Contact restored.');
    }
}
