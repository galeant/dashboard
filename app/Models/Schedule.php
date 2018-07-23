<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';
    protected $primaryKey = 'id';
    protected $hidden = ['id','product_id','created_at','updated_at'];
    protected $fillable = [
        'start_date',
        'end_date',
        'start_hours',
        'end_hours',
        'max_booking_date_time',
        'maximum_booking',
        'product_id'
    ];
}
