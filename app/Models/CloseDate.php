<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CloseDate extends Model {
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
    protected $table = 'close_dates';
    protected $fillable = ['id','date','product_id'];
}
