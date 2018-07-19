<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinationPhoto extends Model
{
    protected $table = 'destination_photos';
    protected $fillable = [
        'destination_id',
        'path',
        'filename',
        'extension'
    ];
}
