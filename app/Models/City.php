<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model {
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
    protected $table = 'cities';
    protected $fillable = ['province_id','name'];

    public function province()
    {
        return $this->hasOne('App\Models\Province','id','province_id');
    }
}
