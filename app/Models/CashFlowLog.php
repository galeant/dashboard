<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashFlowLog extends Model {
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    /**
     * The database table used by the model.
     *
     * @var stringA
     */
    protected $dates = ['deleted_at'];
    protected $table = 'cash_flow_logs';
    protected $fillable = ['cash_flow_id','created_by'];

}
