<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmbulanceTarif extends Model
{
    protected $table = 'ambulance_tarif_by_distance';

    protected $fillable = [
        'ambulance_types_id',
        'provider_id',
        'min_distance',
        'max_distance',
        'tarif'
    ];

    public function ambulance()
    {
        return $this->belongsTo(AmbulanceType::class, 'ambulance_types_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }
}
