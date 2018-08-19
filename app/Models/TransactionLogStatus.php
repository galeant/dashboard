<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionLogStatus extends Model
{
    protected $table = 'transaction_log_status';

    public function status()
    {
        return $this->belongsTo('App\Models\TransactionStatus', 'transaction_status_id','id');
    }
}
