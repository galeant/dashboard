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

    public function customer()
    {
        return $this->hasOne('App\Models\Members','id','user_id');
    }

    public function transaction_log_status()
    {
        return $this->hasMany('App\Models\TransactionLogStatus', 'transaction_id','id');
    }

    public function booking_tours()
    {
        return $this->hasMany('App\Models\BookingTour', 'transaction_id','id');
    }
    public function booking_hotels()
    {
        return $this->hasMany('App\Models\BookingHotel', 'transaction_id','id');
    }
    public function booking_activities()
    {
        return $this->hasMany('App\Models\BookingActivity', 'transaction_id','id');
    }
<<<<<<< HEAD
=======
    public function contact_list()
    {
        return $this->belongsToMany('App\Models\MemberContact','user_contact_transaction','transaction_id','user_contact_id');
    }
>>>>>>> member-fix
}
