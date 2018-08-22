<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionLogStatus extends Model
{
    protected $table = 'transaction_log_status';
    protected $fillable = ['transaction_status_id','transaction_id'];
    public function status()
    {
        return $this->belongsTo('App\Models\TransactionStatus', 'transaction_status_id','id');
    }
}
