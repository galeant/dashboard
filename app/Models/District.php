<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model {
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
    protected $table = 'districts';
    protected $fillable = ['city_id','name'];
}

