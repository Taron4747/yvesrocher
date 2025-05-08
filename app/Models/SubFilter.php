<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubFilter extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'filter_id'];

    public function filter()
    {
        return $this->belongsTo(Filter::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_sub_filter');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_sub_filter');
    }
}