<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingCarRental extends Model
{
    protected $table = 'booking_car_rents';
    
    public function transactions()
    {
        return $this->belongsTo('App\Models\Transaction', 'transaction_id','id');
    }
}
