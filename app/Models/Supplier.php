<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'phone',
        'email',
        'cpf_cnpj',
        'cities_id',
        'address_street',
        'address_number',
        'address_neighborhood',
        'address_complement',
        'stores_id',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'cities_id');
    }
}
