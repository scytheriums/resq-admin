<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\LogsActivity;

class AdditionalService extends Model
{
    use LogsActivity;
    protected $fillable = ['name', 'price'];

    protected $casts = [
        'price' => 'float'
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_additional_services');
    }
}
