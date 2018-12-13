<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model {
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    /**
     * The database table used by the model.
     *
     * @var stringA
     */
    protected $table = 'areas';
    protected $fillable = ['country_id','province_id','area_name','slug','latitude','longitude'];

    public function city()
    {
      return $this->belongsToMany('App\Models\City','area_pivot','area_id','city_id');
    }
    public function province()
    {
        return $this->hasOne('App\Models\Province','id','province_id');
    }
    public function country()
    {
        return $this->hasOne('App\Models\Country','id','country_id');
    }
}
