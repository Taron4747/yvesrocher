<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;


class SettingsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Settings/Index', [
            'filters' => Request::all('search', 'trashed'),
            'contacts' => Auth::user()->account->contacts()
                ->with('organization')
                ->orderByName()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($contact) => [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'phone' => $contact->phone,
                    'city' => $contact->city,
                    'deleted_at' => $contact->deleted_at,
                    'organization' => $contact->organization ? $contact->organization->only('name') : null,
                ]),
        ]);
    }
}
