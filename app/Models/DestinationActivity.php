<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinationActivity extends Model
{
    protected $table = 'destination_activities';
    protected $fillable = [
        'destination_id',
        'activity_tag_id'
    ];
}
