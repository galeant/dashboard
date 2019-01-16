<?php

namespace App\Models\uCar;

use Illuminate\Database\Eloquent\Model;

class uCarDesc extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'car_agency_vehicle_types';

    public function category(){
        return $this->hasMany('App\Models\uCar\uCarCategory','agency_vehicle_category_id','vehicle_category_id');
    }
}
