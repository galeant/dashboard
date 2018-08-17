<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    public function transaction_status()
	{
	    return $this->hasOne('App\Models\TransactionStatus','id','status_id');
    }

    public function transaction_log_status()
    {
        return $this->hasMany('App\Models\TransactionLogStatus', 'transaction_id','id');
    }

    public function booking_tours()
    {
        return $this->hasMany('App\Models\BookingTour', 'transaction_id','id');
    }
}
