<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'states_id',
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'states_id');
    }
}
