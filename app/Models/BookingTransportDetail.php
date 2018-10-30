<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingTransportDetail extends Model
{
    protected $table = 'booking_transport_detail';

    public function origins()
    {
        return $this->belongsTo('App\Models\Airport', 'origin', 'airport_code');
    }

    public function destinations()
    {
        return $this->belongsTo('App\Models\Airport', 'destination', 'airport_code');
    }
}
