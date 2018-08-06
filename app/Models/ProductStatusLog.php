<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStatusLog extends Model
{
    protected $table = 'product_status_logs';
    protected $fillable = ['product_id','status','note'];
}
