<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingTransport extends Model
{
    protected $table = 'booking_transports';


    public function booking_status()
    {
        return $this->hasOne('App\Models\BookingStatus','id','status');
    }
    public function transactions()
    {
        return $this->belongsTo('App\Models\Transaction', 'transaction_id','id');
    }
    public function detail()
    {
        return $this->hasMany('App\Models\BookingTransportDetail','booking_tranport_id', 'id');
    }
}
