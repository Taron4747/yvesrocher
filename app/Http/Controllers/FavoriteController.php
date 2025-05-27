<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        $user = Auth::user();
        $data=$request->all();
        $exists = $user->favorites()->where('product_id',  $data['product_id'])->exists();

        if ($exists) {
            $user->favorites()->where('product_id',  $data['product_id'])->delete();
            $status = 'removed';
        } else {
            $user->favorites()->create([
                'product_id' =>  $data['product_id']
            ]);
            $status = 'added';
        }

        return response()->json([
            'status' => $status
        ]);
    }

    public function list()
    {
        $favorites =  Auth::user()->favoriteProducts()->with('category')->get();

        return response()->json($favorites);
    }
}
