<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    protected $table = 'settlements';
    protected $primaryKey = 'id';
    protected $fillable = [
        'settlement_group_id',
        'booking_number',
        'product_type',
        'product_name',
        'qty',
        'unit_price',
        'total_discount',
        'total_price',
        'total_commission',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'total_paid'
    ];

    public function bookingTour()
    {
        return $this->belongsTo('App\Models\BookingTour','booking_number','booking_number');
    }
    public function bookingHotel()
    {
        return $this->belongsTo('App\Models\BookingHotel','booking_number','booking_number');
    }
    public function bookingActivity()
    {
        return $this->belongsTo('App\Models\BookingActivity','booking_number','booking_number');
    }
    public function bookingCarRental()
    {
        return $this->belongsTo('App\Models\BookingCarRental','booking_number','booking_number');
    }
    public function settlementGroup()
    {
        return $this->belongsTo('App\Models\SettlementGroup','settlement_group_id','id');
    }
}
