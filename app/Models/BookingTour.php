<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingTour extends Model
{
    protected $table = 'booking_tours';
    
    public function transactions()
    {
        return $this->belongsTo('App\Models\Transaction', 'transaction_id','id');
    }
    public function tour()
    {
        return $this->belongsTo('App\Models\Tour', 'product_id','id');
    }
}
