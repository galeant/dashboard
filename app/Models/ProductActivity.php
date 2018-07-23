<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductActivity extends Model
{
    protected $table = 'product_activity';
    protected $fillable = ['product_id','activity_id'];
}
