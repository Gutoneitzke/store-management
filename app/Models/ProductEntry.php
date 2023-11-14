<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductEntry extends Model
{
    use HasFactory;

    protected $table = 'products_entries';

    protected $fillable = [
        'description',
        'qty',
        'total_price',
        'type',
    ];
}
