<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    use HasFactory;

    protected $table = 'products_has_sales';

    protected $fillable = [
        'products_id',
        'sales_id',
        'unity_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }

    public function sale()
    {
        return $this->belongsTo(ProductOutput::class, 'sales_id');
    }
}
