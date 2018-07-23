<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model {
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
    protected $table = 'villages';
    protected $fillable = ['district_id','name','district_name','city_name','city_type','postal_code'];

    public function district()
    {
        return $this->hasOne('App\Models\District','id','district_id');
    }
}

