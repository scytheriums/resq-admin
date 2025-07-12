<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\LogsActivity;

class Driver extends Model
{
    use LogsActivity;
    protected $fillable = [
        'name',
        'phone_number',
        'telegram_chat_id',
        'license_plate',
        'ambulance_type_id',
        'base_address',
        'base_latitude',
        'base_longitude',
        'is_available',
        'province_code',
        'city_code',
        'district_code',
        'village_code'
    ];

    protected $casts = [
        'base_latitude' => 'float',
        'base_longitude' => 'float',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function ambulanceType(): BelongsTo
    {
        return $this->belongsTo(AmbulanceType::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_code', 'code');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'village_code', 'code');
    }

    public function scopeIsAvailable($query)
    {
        return $query->where('is_available', true);
    }
    public function scopeIsUnavailable($query)
    {
        return $query->where('is_available', false);
    }

    public function scopeIsOnDuty($query)
    {
        return $query->whereHas('orders', function ($query) {
            $query->whereIn('order_status', ['assigned_to_driver', 'in_progress_pickup', 'in_progress_deliver', 'confirmed']);
        });
    }
}
