<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalService extends Model
{
    protected $fillable = ['name', 'price'];

    protected $casts = [
        'price' => 'float'
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_additional_services');
    }
}
