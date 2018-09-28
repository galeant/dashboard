<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model {
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
    protected $table = 'provinces';
    protected $fillable = ['country_id','name','cover_path','cover_filename'];

    public function country()
    {
        return $this->hasOne('App\Models\Country','id','country_id');
    }

    public function tourguide()
    {
        return $this->belongsToMany('App\Models\TourGuide','tour_guide_coverages','province_id','tour_guide_id');
    }
    public function cities(){
        return $this->hasMany('App\Models\City','province_id','id');
    }
}

