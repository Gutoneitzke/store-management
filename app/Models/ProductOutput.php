<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOutput extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'qty',
        'type',
        'total_price',
        'customers_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customers_id');
    }
}