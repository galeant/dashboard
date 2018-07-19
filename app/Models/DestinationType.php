<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinationType extends Model
{
    protected $table = 'destination_type';
    protected $hidden = 'id';
    protected $fillable = [
        'name',
        'name_EN'
    ];
}
