x<?php
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
    protected $fillable = ['gendre','firstname','lastname','username','email','phone','status'];
}
