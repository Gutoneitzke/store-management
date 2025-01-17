<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductHasCategory extends Model
{
    use HasFactory;

    protected $table = 'products_has_categories';
    
    protected $fillable = [
        'products_id',
        'categories_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }
}
