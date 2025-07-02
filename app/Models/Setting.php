<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'app_config';
    protected $primaryKey = 'key';
    
    protected $fillable = [
        'key',
        'value',
        'description',
    ];
}
