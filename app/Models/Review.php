<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\LogsActivity;

class Review extends Model
{
    use LogsActivity;
    protected $table = 'reviews';

    protected $fillable = [
        'order_id',
        'user_id',
        'driver_id',
        'rating',
        'comment'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
