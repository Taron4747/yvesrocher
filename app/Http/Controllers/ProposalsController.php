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

        return Redirect::back()->with('success', 'Предложение бизнеса редактирована.');
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
