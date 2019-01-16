<?php

namespace App\Models\uCar;

use Illuminate\Database\Eloquent\Model;

class uCar extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'car_agency_vehicles';

    protected $fillable = ['city_id','province_id','latitude','longitude'];

    public $timestamps = false;

    public function desc(){
        return $this->belongsTo('App\Models\uCar\uCarDesc','vehicle_type_id','id');
    }
    public function city(){
        return $this->hasOne('App\Models\City','id','city_id');
    }
    public function province(){
        return $this->hasOne('App\Models\Province','id','province_id');
    }
    // public function agency(){
    //     return $this->belongsTo('App\Models\uCar\uCarAgency','agency_id','id');
    // }
}
