<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destination extends Model
{   
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $table = 'destinations';
    protected $primaryKey = 'id';
    protected $fillable = [
        'destination_name',
        'description',
        'latitude',
        'longitude',
        'visit_hours',
        'visit_minutes',
        'schedule_type',
        'phone_number',
        'address',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'path',
        'filename',
        'status',
        'destination_type_id'
    ];

    // BETA
    public function provinces(){
        return $this->belongsTo('App\Models\Province', 'province_id', 'id');
    }
    public function cities(){
        return $this->belongsTo('App\Models\City', 'city_id', 'id');
    }
    public function districts(){
        return $this->belongsTo('App\Models\District', 'district_id', 'id');
    }
    public function destination_types(){
        return $this->belongsTo('App\Models\DestinationType', 'destination_type_id', 'id');
    }
    public function villages(){
        return $this->belongsTo('App\Models\Village', 'village_id', 'id');
    }
    public function destination_activities(){
        return $this->belongsToMany('App\Models\ActivityTag', 'destination_activities', 'destination_id', 'activity_tag_id');
    }
    public function destination_photos(){
        return $this->hasMany('App\Models\DestinationPhoto', 'destination_id', 'id');
    }
    public function destination_tips(){
        return $this->belongsToMany('App\Models\DestinationTipsQuestion','destination_tips', 'destination_id','question_id')->withPivot('answer');
    }
    public function destination_schedules(){
        return $this->hasMany('App\Models\DestinationSchedule', 'destination_id','id');
    }
}
