<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmbulanceType extends Model
{
    protected $fillable = [
        'name',
        'base_price'
    ];

    protected $casts = [
        'base_price' => 'float'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
