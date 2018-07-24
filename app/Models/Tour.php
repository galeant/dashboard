<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tour extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'pic_name',
        'pic_phone',
        'product_code',
        'product_name',
        'product_category',
        'product_type',
        'min_person',
        'max_person',
        'meeting_point_address',
        'meeting_point_latitude',
        'meeting_point_longitude',
        'meeting_point_note',
        'term_condition',
        'cancellation_type',
        'min_cancellation_day',
        'cancellation_fee',
        'schedule_type',
        'cover_path',
        'price_idr',
        'price_usd',
        'status',
        'company_id'
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id','id');
    }

    public function schedules()
    {
        return $this->hasMany('App\Models\Schedule', 'product_id','id');
    }

    public function destinations()
    {
        return $this->hasMany('App\Models\ProductDestination','product_id','id');
    }

    public function activities()
    {
        return $this->belongsToMany('App\Models\ActivityTag', 'product_activities','product_id', 'activity_id');
    }

    public function itineraries()
    {
        return $this->hasMany('App\Models\Itinerary', 'product_id','id');
    }

    public function includes()
    {
        return $this->hasMany('App\Models\Includes', 'product_id','id');
    }

    public function excludes()
    {
        return $this->hasMany('App\Models\Excludes', 'product_id','id');
    }

    public function prices()
    {
        return $this->hasMany('App\Models\Price', 'product_id','id');
    }
    // 
    public function image_destination()
    {
        return $this->hasMany('App\Models\ImageDestination', 'product_id','id');
    }
    public function image_activity()
    {
        return $this->hasMany('App\Models\ImageActivity', 'product_id','id');
    }
    public function image_accommodation()
    {
        return $this->hasMany('App\Models\ImageAccommodation', 'product_id','id');
    }
    public function image_other()
    {
        return $this->hasMany('App\Models\ImageOther','product_id','id');
    }
    public function videos()
    {
        return $this->hasMany('App\Models\Videos', 'product_id','id');
    }
}
