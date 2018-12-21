<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model {
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    /**
     * The database table used by the model.
     *
     * @var stringA
     */
    protected $connection = 'mysql';
    protected $table = 'cities';
    protected $fillable = ['province_id','name','type'];

    public function province()
    {
        return $this->hasOne('App\Models\Province','id','province_id');
    }
    public function tour(){
        return $this->belongsToMany('App\Models\Tour','product_destinations','city_id','product_id');
    }
    public function destination(){
        return $this->hasMany('App\Models\Destination','city_id','id');
    }
}
