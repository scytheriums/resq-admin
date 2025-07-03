<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\LogsActivity;

class Setting extends Model
{
    use LogsActivity;
    protected $table = 'app_config';
    protected $primaryKey = 'key';
    
    protected $fillable = [
        'key',
        'value',
        'description',
    ];
}
