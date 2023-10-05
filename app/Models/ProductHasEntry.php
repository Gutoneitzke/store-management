<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductHasEntry extends Model
{
    use HasFactory;

    protected $table = 'products_has_entries';

    protected $fillable = [
        'products_id',
        'entries_id',
        'unity_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }

    public function entry()
    {
        return $this->belongsTo(ProductEntry::class, 'entries_id');
    }
}
