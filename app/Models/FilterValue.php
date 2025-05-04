<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterValue extends Model
{
    public function filter()
    {
        return $this->belongsTo(Filter::class);
    }

}
