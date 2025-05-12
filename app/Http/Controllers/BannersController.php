<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BannersController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Banners/Index', [
            'filters' => Request::all('search', 'trashed'),
            'banners' => Banner::
                filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($banner) => [
                    'id' => $banner->id,
                    'text_arm' => $banner->text_arm,
                    'text_ru' => $banner->text_ru,
                    'text_en' => $banner->text_en,
                    'link' => $banner->link,
                  
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Banners/Create');
    }

    public function store(): RedirectResponse
    {

        $data =Request::all();

$validator = Validator::make(Request::all(), [
    'text_arm' => ['required_if:type,1', 'max:500'],
    'text_ru' => ['required_if:type,1', 'max:500'],
    'text_en' => ['required_if:type,1', 'max:500'],
    'link' => ['required', 'max:500'],
    'image_big' => ['max:500', 'required_if:type,2'],
    'image_medium' => ['max:500', 'required_if:type,2'],
    'image_small' => ['max:500', 'required_if:type,2'],
    'type' => ['required'],
]);
$validator->validate();

if ($data['type'] ==1) {
   Banner::create($data);
}else{
    $path = Request::file('image_big')->store('images', 's3', 'public');
    $image_big = Storage::disk('s3')->url($path);
    $path = Request::file('image_medium')->store('images', 's3', 'public');
    $image_medium = Storage::disk('s3')->url($path);
    $path = Request::file('image_small')->store('images', 's3', 'public');
    $image_small = Storage::disk('s3')->url($path);
    $isnertData =[
        'link'=>$data['link'],
        'type'=>$data['type'],
        'image_small'=>$image_small,
        'image_medium'=>$image_medium,
        'image_big'=>$image_big,

    ];
    Banner::create($isnertData);
}


        return Redirect::route('banners')->with('success', 'Баннер создан ');
    }

    public function edit(Banner $banner): Response
    {
        // dd($banner);
        return Inertia::render('Banners/Edit', [
            'banner' => [
                'id' => $banner->id,
                'TYPE' => $banner->TYPE,
                'text_arm' => $banner->text_arm,
                'text_ru' => $banner->text_ru,
                'text_en' => $banner->text_en,
                'link' => $banner->link,
                'image_big' => $banner->image_big,
                'image_medium' => $banner->image_medium,
                'image_small' => $banner->image_small,
              
            ],
        ]);
    }

    public function update(Organization $organization): RedirectResponse
    {
        $organization->update(
            Request::validate([
                'name' => ['required', 'max:100'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::back()->with('success', 'Organization updated.');
    }

    public function destroy(Organization $organization): RedirectResponse
    {
        $organization->delete();

        return Redirect::back()->with('success', 'Organization deleted.');
    }

    public function restore(Organization $organization): RedirectResponse
    {
        $organization->restore();

        return Redirect::back()->with('success', 'Organization restored.');
    }
}
