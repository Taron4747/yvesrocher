<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    public function values()
    {
        return $this->hasMany(FilterValue::class);
    }
}
