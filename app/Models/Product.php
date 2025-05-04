<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function filterValues()
    {
        return $this->belongsToMany(FilterValue::class);
    }
}
