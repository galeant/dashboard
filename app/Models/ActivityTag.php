<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityTag extends Model
{
    protected $table = 'activity_tags';
    protected $fillable = [
        'name',
        'description'
    ];

    public function products()
    {
        return $this->belongsToMany('App\Models\Tour', 'product_activities','activity_id', 'product_id');
    }
    
}
