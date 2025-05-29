<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
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

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function filters()
    {
        return $this->belongsToMany(Filter::class, 'category_filter');
    }

    public function subFilters()
    {
        return $this->belongsToMany(SubFilter::class, 'category_sub_filter');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
