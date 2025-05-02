<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class);
    }
}
