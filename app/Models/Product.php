<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $appends = ['type'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function filters()
    {
        return $this->belongsToMany(Filter::class, 'product_filter');
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function sizeType()
    {
        return $this->belongsTo(SizeType::class);
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

    public function getTypeAttribute()
    {
        $locale = app()->getLocale();

        if (!$this->relationLoaded('sizeType')) {
            $this->load('sizeType');
        }

        $field = match ($locale) {
            'ru' => 'name_ru',
            'en' => 'name_en',
            'hy', 'arm' => 'name_arm',
            default => 'name_ru',
        };

        return $this->sizeType?->{$field};
    }
}
