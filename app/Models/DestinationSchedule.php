<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DestinationSchedule extends Model
{
	use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $table = 'destination_schedules';
    protected $fillable = [
        'destination_id',
        'destination_schedule_day',
        'destination_schedule_condition',
        'destination_schedule_start_hours',
        'destination_schedule_end_hours'
    ];
}
