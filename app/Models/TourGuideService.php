<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class TourGuideService extends Model {
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
    protected $table = 'tour_guide_services';
    protected $fillable = ['name','link_rewrite'];

}

