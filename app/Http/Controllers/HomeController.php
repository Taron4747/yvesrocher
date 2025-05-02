<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\URL;
use Inertia\Response;
use App\Models\Category;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Organizations/Index', [
            'categories' =>Category::with('children.children')->whereNull('parent_id')->get()
        ]);
    }

   

   

   
   

   
}
