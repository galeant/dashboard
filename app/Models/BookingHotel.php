<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingHotel extends Model
{
    protected $table = 'booking_hotels';
    
    public function booking_status()
    {
        return $this->hasOne('App\Models\BookingStatus','id','status');
    }
    public function transactions()
    {
        return $this->belongsTo('App\Models\Transaction', 'transaction_id','id');
    }
    public function available_rooms(){
        return $this->hasMany('App\Models\RoomAvailable','room_id','room_id');
    }
    public function reservation(){
    	return $this->hasOne('App\Models\ReservationHotel','booking_number','booking_number');
    }

}
