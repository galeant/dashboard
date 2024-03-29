<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingStatus extends Model {
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
    protected $table = 'booking_status';
    protected $fillable = ['name','color'];
}
