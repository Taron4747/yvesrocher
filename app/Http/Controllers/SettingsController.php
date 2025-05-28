<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index(): Response
    {
        $settings =Setting::all();
        // dd($settings->where('name','delivery_price'),array_values($settings->where('name','delivery_discount')->toArray()));
        return Inertia::render('Settings/Index', [
            'settings' => [
                'delivery_price'=>array_values($settings->where('name','delivery_price')->toArray())[0]['value'],
                'delivery_discount'=>array_values($settings->where('name','delivery_discount')->toArray())[0]['value'],
            ]
              
        ]);
    }


    public function update()  {
        $data= Request::all();
        Request::validate([
            'delivery_price' => ['required', 'numeric'],
            'delivery_discount' => ['required', 'numeric'],
           
        
        ]);
        Setting::where('name','delivery_price')->update(['value'=>$data['delivery_price']]);
        Setting::where('name','delivery_discount')->update(['value'=>$data['delivery_discount']]);
        return Redirect::route('settings')->with('success', 'Настройки обновлены.');

    }
}
