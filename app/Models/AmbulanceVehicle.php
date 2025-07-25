<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AmbulanceVehicle extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'vehicle_name'
    ];

    public function ambulanceTypes()
    {
        return $this->hasMany(AmbulanceType::class, 'vehicle_id', 'id');
    }
}
