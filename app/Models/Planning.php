<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planning extends Model
{
    //
    protected $table = 'plannings';

    public function planning_days(){
        return $this->hasMany('App\Models\PlanningDay', 'planning_id','id');
    }
}
