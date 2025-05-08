<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_filter');
    }

    public function subFilters()
    {
        return $this->hasMany(SubFilter::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_filter');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name_ru', 'like', '%'.$search.'%');
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
