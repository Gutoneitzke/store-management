<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierHasProduct extends Model
{
    use HasFactory;

    protected $table = 'supplier_has_products';

    protected $fillable = [
        'supplier_id',
        'products_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }
}
