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
use App\Rules\MaxActiveLimit;

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
                    'title_ru' => $banner->title_ru,
                    'text_ru' => $banner->text_ru,
                    'position' => $banner->position,
                    'link' => $banner->link,
                    'is_active' => $banner->is_active,
                  
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
            'text_arm' => ['required', 'max:500'],
            'text_ru' => ['required', 'max:500'],
            'text_en' => ['required', 'max:500'],
            'title_arm' => ['required', 'max:500'],
            'title_ru' => ['required', 'max:500'],
            'title_en' => ['required', 'max:500'],
            'position' => ['required', 'integer'],
            // 'proposition_arm' => ['required', 'max:500'],
            // 'proposition_ru' => ['required', 'max:500'],
            // 'proposition_en' => ['required', 'max:500'],
            'link' => ['required','regex:/^https?:\/\/(www\.)?yves-rocher\.am(\/.*)?$/i'],
            'image_big' => ['required' ],
            'image_medium' => ['required' ],
            'image_small' => ['required' ],
            'bottom_image' => ['required' ],
            'is_active' => ['required', 'boolean', new MaxActiveLimit(\App\Models\Banner::class)],
        ], [
            'link.regex' => 'Неверный формат ссылки. URL должен начинаться с: https://yves-rocher.am/',
        ]);
    $validator->validate();


//    Banner::create($data);

    $path = Request::file('image_big')[0]->store('images', 's3', 'public');
    $image_big = Storage::disk('s3')->url($path);
    $path = Request::file('image_medium')[0]->store('images', 's3', 'public');
    $image_medium = Storage::disk('s3')->url($path);
    $path = Request::file('image_small')[0]->store('images', 's3', 'public');
    $image_small = Storage::disk('s3')->url($path);
    $path = Request::file('bottom_image')[0]->store('images', 's3', 'public');
    $bottom_image = Storage::disk('s3')->url($path);
    $data['image_small']= $image_small;
    $data['image_medium']= $image_medium;
    $data['image_big']= $image_big;
    $data['bottom_image']= $bottom_image;

   
    Banner::create($data);



        return Redirect::route('banners')->with('success', 'Баннер создан ');
    }

    public function edit(Banner $banner): Response
    {
        return Inertia::render('Banners/Edit', [
            'banner' => [
                'id' => $banner->id,
                'type' => $banner->type,
                'text_arm' => $banner->text_arm,
                'text_ru' => $banner->text_ru,
                'text_en' => $banner->text_en,

                'title_arm' => $banner->title_arm,
                'title_ru' => $banner->title_ru,
                'title_en' => $banner->title_en,

                'proposition_arm' => $banner->proposition_arm,
                'proposition_ru' => $banner->proposition_ru,
                'proposition_en' => $banner->proposition_en,

                'link' => $banner->link,
                'image_big' => $banner->image_big,
                'image_medium' => $banner->image_medium,
                'image_small' => $banner->image_small,
                'bottom_image' => $banner->bottom_image,
                'is_active' => $banner->is_active,
                'position' => $banner->position,
              
            ],
        ]);
    }

    public function update(Banner $banner): RedirectResponse
    {
        $data =Request::all();
        $banner->update(
            // Request::validate([
            //     'text_arm' => ['required', 'max:500'],
            //     'text_ru' => ['required', 'max:500'],
            //     'text_en' => ['required', 'max:500'],
            //     'title_arm' => ['required', 'max:500'],
            //     'title_ru' => ['required', 'max:500'],
            //     'title_en' => ['required', 'max:500'],
            //     'position' => ['required', 'integer'],
        
            //     // 'proposition_arm' => ['required', 'max:500'],
            //     // 'proposition_ru' => ['required', 'max:500'],
            //     // 'proposition_en' => ['required', 'max:500'],
            //     'link' => ['required','regex:/^https?:\/\/(www\.)?yves-rocher\.am(\/.*)?$/i'],

            //     'is_active' => ['required', 'boolean', new MaxActiveLimit(\App\Models\Banner::class)],
                
            // ])
            Request::validate([
                'text_arm' => ['required', 'max:500'],
                'text_ru' => ['required', 'max:500'],
                'text_en' => ['required', 'max:500'],
                'title_arm' => ['required', 'max:500'],
                'title_ru' => ['required', 'max:500'],
                'title_en' => ['required', 'max:500'],
                'position' => ['required', 'integer'],
                // 'proposition_arm' => ['required', 'max:500'],
                // 'proposition_ru' => ['required', 'max:500'],
                // 'proposition_en' => ['required', 'max:500'],
                'link' => ['required','regex:/^https?:\/\/(www\.)?yves-rocher\.am(\/.*)?$/i'],
                'is_active' => ['required', 'boolean', new MaxActiveLimit(\App\Models\Banner::class)],
            ], [
                'link.regex' => 'Неверный формат ссылки. URL должен начинаться с: https://yves-rocher.am/',
            ])
        );
     
        if (isset(Request::file('image_big')[0])) {
            $path = Request::file('image_big')[0]->store('images', 's3', 'public');
            $image_big = Storage::disk('s3')->url($path);
            $banner->update(['image_big'=>$image_big]);
        }
        if (isset(Request::file('image_small')[0])) {
            $path = Request::file('image_small')[0]->store('images', 's3', 'public');
            $image_small = Storage::disk('s3')->url($path);
            $banner->update(['image_small'=>$image_small]);
        }
        if (isset(Request::file('image_medium')[0])) {
            $path = Request::file('image_medium')[0]->store('images', 's3', 'public');
            $image_medium = Storage::disk('s3')->url($path);
            $banner->update(['image_medium'=>$image_medium]);
        }
        if (isset(Request::file('bottom_image')[0])) {
            $path = Request::file('bottom_image')[0]->store('images', 's3', 'public');
            $bottom_image = Storage::disk('s3')->url($path);
            $banner->update(['bottom_image'=>$bottom_image]);
        }

        return Redirect::back()->with('success', 'Баннер редактирован ');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $banner->delete();

        return redirect()->route('banners')->with('success', 'Баннер удален.');

        return Redirect::back()->with('success', 'Баннер удален.');
    }

    public function restore(Banner $banner): RedirectResponse
    {
        $banner->restore();

        return Redirect::back()->with('success', 'Баннер восстановлен.');
    }
    public function changeBanner()
    {
        $data =Request::all();
        Banner::where('id',$data['id'])->update(['position'=>$data['position']]);

    } 

    public function bannerActivate()
    {
        $data =Request::all();
        // dd($data);
        if ($data['is_active'] ==0) {
            $bannersCount = Banner::where('is_active',1)->count();
            if ($bannersCount ==5) {
                return response()->json([    'status' => 'warning','message'=>'Невозможно одновременно иметь больше пяти активных баннеров ']);
                
            }
            Banner::where('id',$data['id'])->update(['is_active'=>1]);

        }else{
        Banner::where('id',$data['id'])->update(['is_active'=>0]);

        }

    }
}
