<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Members extends Model {
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
    protected $table = 'users';
    protected $fillable = ['gender','firstname','lastname','username','email','phone','status'];

    public function transactions(){
        return $this->hasMany('App\Models\Transaction', 'user_id','id');
    }
}
