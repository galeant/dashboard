<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $table = 'product_types';
    protected $fillable = [
        'name',
        'code',
        'creatd_at'
    ];
    public function companies()
    {
        return $this->belongsToMany('App\User');
    }
}
