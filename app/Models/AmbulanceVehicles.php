<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\LogsActivity;

class AmbulanceVehicles extends Model
{
    use LogsActivity;
    protected $fillable = [
        'name',
    ];
}
