<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingHotel extends Model
{
    protected $table = 'booking_hotels';
    
    public function booking_status()
	{
	    return $this->hasOne('App\Models\TransactionStatus','id','status');
    }
    public function transactions()
    {
        return $this->belongsTo('App\Models\Transaction', 'transaction_id','id');
    }
    public function available_rooms(){
    	return $this->hasMany('App\Models\RoomAvailable','room_id','room_id');
    }

}
