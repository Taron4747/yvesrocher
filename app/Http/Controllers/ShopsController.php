<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShopsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Shops/Index', [
            'filters' => Request::all('search', 'trashed'),
            'shops' => Shop::
                orderBy('id')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($shop) => [
                    'id' => $shop->id,
                    'address_ru' => $shop->address_ru,
                    'phone' => $shop->phone,
                    'time' => $shop->time_from.'-'. $shop->time_to,
                    'deleted_at' => $shop->deleted_at,
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Shops/Create');
    }

    public function store(): RedirectResponse
    {
        Shop::create(
            Request::validate([
                'phone' => ['required', 'max:50'],
                'address_ru' => ['nullable', 'max:500'],
                'address_en' => ['nullable', 'max:500'],
                'address_arm' => ['nullable', 'max:500'],
                'time_from' => ['required', 'max:50'],
                'time_to' => ['required', 'max:50'],
            ])
        );

        return Redirect::route('shops')->with('success', 'Магазин создан.');
    }

    public function edit(Shop $shop): Response
    {
        return Inertia::render('Shops/Edit', [
            'shop' => [
                'id' => $shop->id,
                'address_arm' => $shop->address_arm,
                'address_en' => $shop->address_en,
                'address_ru' => $shop->address_ru,
                'phone' => $shop->phone,
                'time_from' => $shop->time_from,
                'time_to' => $shop->time_to,
                
            ],
        ]);
    }

    public function update(Shop $shop): RedirectResponse
    {
        $shop->update(
            Request::validate([
                'address_arm' => ['required', 'max:500'],
                'address_en' => ['required', 'max:500'],
                'phone' => ['required', 'max:50'],
                'address_ru' => ['required', 'max:500'],
                'time_from' => ['required', 'max:50'],
                'time_to' => ['required', 'max:50'],
            ])
        );

        return Redirect::back()->with('success', 'Магазин отредактирован.');
    }

    public function destroy(Shop $shop): RedirectResponse
    {
        $shop->delete();

        return Redirect::back()->with('success', 'Магазин удален.');
    }

    public function restore(Shop $shop): RedirectResponse
    {
        $shop->restore();

        return Redirect::back()->with('success', 'Organization restored.');
    }
}
