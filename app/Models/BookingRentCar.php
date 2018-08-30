<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingRentCar extends Model
{
    protected $table = 'booking_rent_cars';
    
    public function booking_status()
	{
	    return $this->hasOne('App\Models\TransactionStatus','id','status');
    }
    
    public function transactions()
    {
        return $this->belongsTo('App\Models\Transaction', 'transaction_id','id');
    }
}
