<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model {
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
    protected $table = 'provinces';
    protected $fillable = ['country_id','name'];

    public function country()
    {
        return $this->hasOne('App\Models\Country','id','country_id');
    }
}
