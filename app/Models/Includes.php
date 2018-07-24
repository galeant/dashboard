<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Includes extends Model
{
    protected $table = 'price_includes';
    protected $hidden = ['product_id','created_at','updated_at'];
    protected $fillable = ['product_id','name'];
}
