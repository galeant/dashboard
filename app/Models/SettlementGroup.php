<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettlementGroup extends Model
{
    protected $table = 'settlement_groups';
    protected $primaryKey = 'id';
    protected $fillable = [
        'total_price',
        'total_commission',
        'total_paid',
        'note',
        'paid_at',
        'status',
        'start_date',
        'end_date'
    ];

    public function settlement()
    {
        return $this->hasMany('App\Models\Settlement','settlement_group_id','id');
    }
    
}
