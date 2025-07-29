<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'pic_name',
        'address',
        'is_active'
    ];
}
