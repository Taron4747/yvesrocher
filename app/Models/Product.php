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
}
