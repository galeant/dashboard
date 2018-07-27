<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierRole extends Model {
    // use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = ['deleted_at'];
    /**
     * The database table used by the model.
     *
     * @var stringA
     */
    protected $table = 'supplier_roles';
    protected $fillable = ['id','name'];

    public function suppliers(){
        return $this->hasMany('App\Models\Supplier');
    }
}


