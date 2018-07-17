<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class TourGuideCoverage extends Model {
    // use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = ['deleted_at'];
    /**
     * The database table used by the model.
     *
     * @var stringA
     */
    protected $table = 'tour_guide_coverages';
    protected $fillable = ['province_id','province_name','city_id','city_name','tour_guide_id'];

    public function city()
    {
        return $this->hasOne('App\Models\City','id','city_id');
    }
    public function province()
    {
        return $this->hasOne('App\Models\Province','id','province_id');
    }

}

