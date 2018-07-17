<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourGuide extends Model {
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
    protected $table = 'tour_guides';
    protected $fillable = ['company_id','country_id','tour_guide_service_id','fullname','age','salutation','nationality','email','phone','avatar','experience_year','language','guide_license','guide_assosciation'];

    public function coverage()
    {
        return $this->hasMany('App\Models\TourGuideCoverage','tour_guide_id','id');
    }
    public function price()
    {
        return $this->hasMany('App\Models\TourGuideServicePrice','tour_guide_id','id');
    }
}

