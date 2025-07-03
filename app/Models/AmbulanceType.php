<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\LogsActivity;

class AmbulanceType extends Model
{
    use LogsActivity;
    protected $fillable = [
        'name',
        'tarif_dalam_kota',
        'tarif_km_luar_kota',
        'tarif_km_luar_provinsi',
        'free_tarif_for_purpose'
    ];

    protected $casts = [
        'tarif_dalam_kota' => 'float',
        'tarif_km_luar_kota' => 'float',
        'tarif_km_luar_provinsi' => 'float',
        'free_tarif_for_purpose' => 'array',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
