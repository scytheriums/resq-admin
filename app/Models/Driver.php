<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'status',
        'average_rating'
    ];

    protected $casts = [
        'average_rating' => 'float'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
