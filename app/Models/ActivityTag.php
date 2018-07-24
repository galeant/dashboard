<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityTag extends Model
{
    protected $table = 'activity_tags';
    protected $fillable = [
        'name',
        'description'
    ];
}
