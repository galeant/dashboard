<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingRentCar extends Model
{
    protected $table = 'booking_rent_cars';
    
    public function transactions()
    {
        return $this->belongsTo('App\Models\Transaction', 'transaction_id','id');
    }
    // public function car_rental(){
    //     return $this->belongsTo('App\Models\Transaction', 'transaction_id','id');
    // }
}
