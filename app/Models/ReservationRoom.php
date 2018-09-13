<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationRoom extends Model {
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
    protected $connection = 'mysql2';
    protected $table = 'booking_rooms';
    protected $fillable = ['booking_number','hotel_id','room_id','room_numbers','checkin','checkout','adults','children','rooms','price','discount','extra_beds','extra_beds_charge','meal_plan_id','meal_plan_price','email_notify'
    ];
}

