<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'status',
        'payment_status',
        'user_id',
        'driver_id',
        'ambulance_type_id',
        'purpose_id',
        'pickup_location',
        'destination_location',
        'notes',
        'base_price',
        'booking_fee',
        'additional_services_fee',
        'total_bill'
    ];

    protected $casts = [
        'pickup_location' => 'array',
        'destination_location' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function ambulanceType()
    {
        return $this->belongsTo(AmbulanceType::class);
    }

    public function purpose()
    {
        return $this->belongsTo(Purpose::class);
    }

    public function additionalServices()
    {
        return $this->belongsToMany(AdditionalService::class, 'order_additional_services');
    }
}
