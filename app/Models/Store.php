<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cnpj',
        'description',
        'address',
        'cities_id',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'cities_id');
    }
}
