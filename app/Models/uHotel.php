<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class uHotel extends Model
{
    protected $connection = 'mysql2';

    protected $table = /*'hotel.uhb_*/'hotels';
    
    protected $fillable = ['city_id','province_id','latitude','longitude'];

    public function city(){
        return $this->belongsTo('App\Models\City','city_id','id');
    }
    public function province(){
        return $this->belongsTo('App\Models\Province','province_id','id');
    }
    public function desc(){
        return $this->hasOne('App\Models\uHotelDescription','hotel_id','id');
    }
    
    public $timestamps = false;
    //
}
