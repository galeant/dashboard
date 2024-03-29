<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';
    protected $primaryKey = 'id';
    protected $hidden = [/*'id',*/'product_id','created_at','updated_at'];
    protected $fillable = [
        'start_date',
        'end_date',
        'start_hours',
        'end_hours',
        'maximum_booking',
        'product_id'
    ];

    public function tour()
    {
        return $this->hasOne('App\Models\Tour','id','product_id');
    }
    public function bookings()
    {
        return $this->hasMany('App\Models\BookingTour','schedule_id','id');
    }
}
