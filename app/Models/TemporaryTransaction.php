<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryTransaction extends Model
{
    protected $table = 'temporary_transactions';

    public function transaction_status()
	{
	    return $this->hasOne('App\Models\TransactionStatus','id','status_id');
    }

    public function customer()
    {
        return $this->hasOne('App\Models\Members','id','user_id');
    }
    
    public function planning()
    {
        return $this->hasOne('App\Models\Planning','temporary_transaction_id','id');
    }
    public function booking_tours()
    {
        return $this->hasMany('App\Models\BookingTour', 'temp_transaction_id','id');
    }
    public function booking_hotels()
    {
        return $this->hasMany('App\Models\BookingHotel', 'temp_transaction_id','id');
    }
    public function booking_activities()
    {
        return $this->hasMany('App\Models\BookingActivity', 'temp_transaction_id','id');
    }
}
