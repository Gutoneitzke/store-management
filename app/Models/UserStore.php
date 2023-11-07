<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStore extends Model
{
    use HasFactory;

    protected $table = 'users_has_stores';

    protected $fillable = [
        'users_id',
        'stores_id',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'stores_id');
    }
}
