<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinationTipsQuestion extends Model
{
    protected $table = 'destination_tips_questions';
    // protected $hidden = 'id';
    protected $fillable = [
        'question'
    ];
}
