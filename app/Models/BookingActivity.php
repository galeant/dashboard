<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingActivity extends Model
{
    protected $table = 'booking_activities';

    public function transactions()
    {
        return $this->belongsTo('App\Models\Transaction', 'transaction_id','id');
    }
    
    public function activities()
    {
        return $this->belongsTo('App\Models\Destination', 'activity_id','id');
    }
}
