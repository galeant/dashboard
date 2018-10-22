<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyLevel extends Model
{
    protected $table = 'company_levels';
    protected $fillable = [
        'name'
    ];
    public function companyLevelCommission(){
        return $this->hasMany('App\Models\CompanyLevelCommission','company_level_id','id');
    }
}
