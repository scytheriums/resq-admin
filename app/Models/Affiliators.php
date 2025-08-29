<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Affiliators extends Model
{
    protected $table = 'affiliators';
    
    protected $fillable = [
        'code',
        'name',
        'phone_number',
        'email',
        'province_code',
        'city_code',
        'district_code',
        'village_code',
        'full_address',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

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

    public function getAddressAttribute(): string
    {
        $address = [];
        if ($this->village) $address[] = $this->village->name;
        if ($this->district) $address[] = $this->district->name;
        if ($this->city) $address[] = $this->city->name;
        if ($this->province) $address[] = $this->province->name;
        
        return implode(', ', $address);
    }
}
