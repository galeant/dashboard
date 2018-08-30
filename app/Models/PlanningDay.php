<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanningDay extends Model
{
    //
    protected $table = 'planning_days';

    public function planning_days(){
        return $this->hasMany('App\Models\PlanningDestination', 'planning_day_id','id');
    }
}
