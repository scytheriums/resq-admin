<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    private $paymentStates = [
        'booking_fee_pending' => [
            'label' => 'Menunggu Booking Fee',
            'class' => 'warning'
        ],
        'booking_fee_paid' => [
            'label' => 'Booking Fee Lunas',
            'class' => 'info'
        ],
        'final_payment_pending' => [
            'label' => 'Tagihan Belum Dibayar',
            'class' => 'warning'
        ],
        'final_payment_paid' => [
            'label' => 'Tagihan Lunas',
            'class' => 'success'
        ],
    ];

    private $states = [
        'created' => [
            'label' => 'Pesanan Dibuat',
            'class' => 'warning'
        ],
        'booked' => [
            'label' => 'Booked',
            'class' => 'info'
        ],
        'confirmed' => [
            'label' => 'Dikonfirmasi Admin',
            'class' => 'primary'
        ],
        'assigned_to_driver' => [
            'label' => 'Driver Ditugaskan',
            'class' => 'secondary'
        ],
        'in_progress_pickup' => [
            'label' => 'Menuju Titik Jemput',
            'class' => 'info'
        ],
        'in_progress_deliver' => [
            'label' => 'Menuju Titik Tujuan',
            'class' => 'info'
        ],      
        'completed' => [
            'label' => 'Selesai',
            'class' => 'success'
        ],
        'cancelled_by_user' => [
            'label' => 'Dibatalkan',
            'class' => 'danger'
        ],
        'cancelled_by_system' => [
            'label' => 'Dibatalkan',
            'class' => 'danger'
        ],
    ];

    protected $fillable = [
        'order_number',
        'user_id',
        'driver_id',
        'ambulance_type_id',
        'purpose_id',
        'service_type',
        'name',
        'whatsapp_number',
        'notes',
        'pickup_address',
        'pickup_latitude',
        'pickup_longitude',
        'pickup_province',
        'pickup_city',
        'pickup_district',
        'pickup_subdistrict',
        'pickup_postal_code',
        'destination_address',
        'destination_latitude',
        'destination_longitude',
        'destination_province',
        'destination_city',
        'destination_district',
        'destination_subdistrict',
        'destination_postal_code',
        'order_status',
        'payment_status',
        'base_price',
        'booking_fee',
        'additional_services_fee',
        'total_bill',
        'cancellation_reason',
        'order_date'
    ];

    protected $casts = [
        'order_date' => 'datetime',
    ];

    protected $appends = [
        'order_status_label',
        'order_status_class',
        'payment_status_label',
        'payment_status_class',
    ];
    
    public function getOrderStatusLabelAttribute() : String|Null
    {
        return ($this->order_status && isset($this->states[$this->order_status])) 
        ? $this->states[$this->order_status]['label'] 
        : null;
    }

    public function getOrderStatusClassAttribute() : String|Null
    {
        return ($this->order_status && isset($this->states[$this->order_status])) 
        ? $this->states[$this->order_status]['class'] 
        : null;
    }

    public function getPaymentStatusLabelAttribute() : String|Null
    {
        return ($this->payment_status && isset($this->paymentStates[$this->payment_status])) 
            ? $this->paymentStates[$this->payment_status]['label'] 
            : null;
    }

    public function getPaymentStatusClassAttribute() : String|Null
    {
        return ($this->payment_status && isset($this->paymentStates[$this->payment_status])) 
            ? $this->paymentStates[$this->payment_status]['class'] 
            : null;
    }

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
        return $this->belongsToMany(AdditionalService::class, 'order_additional_services', 'order_id', 'service_id')
            ->withPivot('price');
    }
}
