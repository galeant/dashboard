<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DestinationTipsQuestion extends Model
{
    protected $table = 'destination_tips_questions';
    // protected $hidden = 'id';
    protected $fillable = [
        'question'
    ];
}
