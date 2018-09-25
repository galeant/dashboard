<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingRentCar extends Model
{
    protected $table = 'booking_rent_cars';
    
    public function booking_status()
	{
	    return $this->hasOne('App\Models\BookingStatus','id','status');
    }
    public function transactions()
    {
        return $this->belongsTo('App\Models\Transaction', 'transaction_id','id');
    }
    public function reservation()
    {
        return $this->belongsTo('App\Models\ReservationRentCar', 'booking_number','reservation_number');
    }
}
