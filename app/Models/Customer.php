<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'cpf_cnpj',
        'gender',
        'cities_id',
        'address_street',
        'address_number',
        'address_neighborhood',
        'address_complement',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'cities_id');
    }
}
