<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FilterCategory extends Model
{
    protected $table = 'filter_category';
    protected $fillable = ['category_id', 'filter_id', 'filter_value_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function filter()
    {
        return $this->belongsTo(Filter::class);
    }

    public function value()
    {
        return $this->belongsTo(FilterValue::class, 'filter_value_id');
    }
}
