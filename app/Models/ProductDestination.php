<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDestination extends Model
{
    protected $table = 'product_destinations';
    protected $fillable = ['product_id','province_id','city_id','destination_id'];

    // BETA
    public function province(){
        return $this->belongsTo('App\Models\Province','province_id', 'id');
    }
    public function city(){
        return $this->belongsTo('App\Models\City','city_id', 'id');
    }
    public function dest(){
        return $this->belongsTo('App\Models\Destination','destination_id', 'id');
    }
}
