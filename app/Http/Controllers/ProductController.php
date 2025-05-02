<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Attribute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;
class ProductController extends Controller
{
    public function index(Request $request)
{
    $query = Product::with('attributeValues.attribute');

    if ($filters = $request->input('filters')) {
        foreach ($filters as $attribute => $values) {
            $query->whereHas('attributeValues.attribute', function ($q) use ($attribute, $values) {
                $q->where('name', $attribute)
                  ->whereIn('attribute_values.value', $values);
            });
        }
    }

    return Inertia::render('Products/Index', [
        'products' => $query->paginate(12),
        'filters' => Attribute::with('values')->where('filterable', true)->get(),
        'selected' => $request->filters ?? [],
    ]);
}

}
