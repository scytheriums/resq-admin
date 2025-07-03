<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\LogsActivity;

class Purpose extends Model
{
    use LogsActivity;
    protected $fillable = ['name', 'tarif'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
