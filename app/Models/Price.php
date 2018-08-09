<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $table = 'prices';
    protected $primaryKey = 'id';

    protected $hidden = [/*'id',*/'product_id', 'created_at', 'updated_at'];
    protected $fillable = [
        'number_of_person',
        'price_idr',
        'price_usd',
        'product_id'
    ];
    public function product()
    {
        return $this->belongsTo('App\Models\tour', 'product_id','id');
    }
}
