<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table = 'campaigns';

    public function campaign_products()
    {
        return $this->hasMany('App\Models\CampaignProduct', 'campaign_id','id');
    }

}
