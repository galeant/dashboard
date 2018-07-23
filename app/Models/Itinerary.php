<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    protected $table = 'itineraries';
    protected $primaryKey = 'id';
    protected $hidden = ['id', 'product_id', 'created_at', 'updated_at'];
    protected $fillable = [
        'day',
        'start_time',
        'end_time',
        'description',
        'product_id'
    ];
}
