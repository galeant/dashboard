<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinationSchedule extends Model
{
    protected $table = 'destination_schedules';
    protected $fillable = [
        'destination_id',
        'destination_schedule_day',
        'destination_schedule_condition',
        'destination_schedule_start_hours',
        'destination_schedule_end_hours'
    ];
}
