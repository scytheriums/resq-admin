<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'actor_id',
        'actor_type',
        'action_type',
        'description',
        'details',
        'ip_address'
    ];

    protected $casts = [
        'details' => 'json'
    ];


    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id', 'id');
    }
}
