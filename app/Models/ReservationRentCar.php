<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationRentCar extends Model {
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
    protected $table = 'car_reservations';
    public $timestamps = false;
    protected $fillable = ['reservation_number','car_agency_id','car_vehicle_type_id','car_vehicle_id','location_from_id','location_to_id','date_from','time_from','date_to','time_to','reservation_description','reservation_price','pre_payment_type','pre_payment_value','vat_fee','vat_percent','extras','extras_fee','reservation_total_price','reservation_paid','additional_payment','currency','customer_id','is_admin_reservation','payment_date','payment_type','payment_method','additional_info','cc_type','cc_holder_name','cc_number','cc_expires_month','cc_expires_year','cc_cvv_code','status','status_changed','status_description','email_sent','created_date'
    ];
}

