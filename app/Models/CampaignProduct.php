<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignProduct extends Model
{
    protected $table = 'campaign_products';

    public function product_types()
    {
        return $this->belongsTo('App\Models\ProductType','product_type','id');
    }
    public function campaigns()
    {
        return $this->belongsTo('App\Models\Campaign', 'campaign_id', 'id');
    }
    public function tours()
    {
        return $this->belongsTo('App\Models\Tour', 'product_id','id');
    }
}
