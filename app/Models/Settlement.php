<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    protected $table = 'settlements';
    protected $primaryKey = 'id';
    protected $fillable = [
        'booking_number',
        'product_type',
        'product_name',
        'qty',
        'unit_price',
        'total_discount',
        'total_price',
        'total_commission',
        'status',
        'due_date',
        'paid_at',
        'batch',
    ];

    public function bookingTour()
    {
        return $this->belongsTo('App\Models\BookingTour','booking_number','booking_number');
    }
    public function bookingHotel()
    {
        return $this->belongsTo('App\Models\BookingHotel','booking_number','booking_number');
    }
    public function bookingActivity()
    {
        return $this->belongsTo('App\Models\BookingActivity','booking_number','booking_number');
    }
    public function bookingCarRental()
    {
        return $this->belongsTo('App\Models\BookingCarRental','booking_number','booking_number');
    }
}
