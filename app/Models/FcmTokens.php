<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\LogsActivity;

class FcmTokens extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'device_info',
        'created_at',
        'last_used_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
