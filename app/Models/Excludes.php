<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Excludes extends Model
{
    protected $table = 'price_excludes';
    protected $hidden = ['id','created_at','updated_at'];
    protected $fillable = ['product_id','name'];
}
