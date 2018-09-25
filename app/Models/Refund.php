<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $table = 'refund';
    protected $fillable = ['transaction_id','booking_number','product_type','total_payment','note'];
}
