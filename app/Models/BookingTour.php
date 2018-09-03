<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingTour extends Model
{
    protected $table = 'booking_tours';

    public function booking_status()
    {
        return $this->hasOne('App\Models\BookingStatus','id','status');
    }
    public function transactions()
    {
        return $this->belongsTo('App\Models\Transaction', 'transaction_id','id');
    }
    public function tours()
    {
        return $this->belongsTo('App\Models\Tour', 'product_id','id');
    }
}
