<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\LogsActivity;

class Destination extends Model
{
    use HasFactory, LogsActivity; 

    protected $table = 'destinations';
    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'province',
        'city',
        'district',
        'subdistrict',
        'postal_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
