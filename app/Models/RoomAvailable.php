<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomAvailable extends Model {
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
    protected $table = 'rooms_availabilities';
    protected $fillable = ['room_id','y','m','d1','d2','d3','d4','d5','d6','d7','d8','d9','d10','d11','d12','d13','d14','d15','d16','d17','d18','d19','d20','d21','d22','d23','d24','d25','d26','d27','d28','d29','d30','d31'
    ];
}

