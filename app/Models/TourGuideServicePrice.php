<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourGuideServicePrice extends Model {
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
    protected $table = 'tour_guide_service_prices';
    protected $fillable = ['tour_guide_id','tour_guide_service_id','service_name','rate_per_day','rate_per_day2'];
}

