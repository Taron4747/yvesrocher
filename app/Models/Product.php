<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function filters()
    {
        return $this->belongsToMany(Filter::class, 'product_filter');
    }

    public function subFilters()
    {
        return $this->belongsToMany(SubFilter::class, 'product_sub_filter');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name_en', 'like', '%'.$search.'%')
                    ->orWhere('name_ru', 'like', '%'.$search.'%')
                    ->orWhere('name_arm', 'like', '%'.$search.'%');
                   
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
