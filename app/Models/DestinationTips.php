<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinationTips extends Model
{
    protected $table = 'destination_tips';
    protected $hidden = 'id';
    protected $fillable = [
        'destination_id',
        'question_id',
        'answer'
    ];
}
