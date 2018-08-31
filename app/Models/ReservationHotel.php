<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationHotel extends Model {
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
    protected $connection= 'mysql2';
    protected $table = 'bookings';
    protected $fillable = ['booking_number','hotel_reservation_id','booking_description','discount_percent','discount_fee','guests_discount','nights_discount','order_price','pre_payment_type','pre_payment_value','vat_fee','vat_percent','initial_fee','guest_tax','payment_sum','additional_payment','currency','rooms_amount','customer_id','is_admin_reservation','created_by_admin_id','transaction_number','created_date','payment_date','cancel_payment_date','payment_type','payment_method','discount_campaign_id','additional_info','regional_menager_comment','extras','extras_fee','cc_type','cc_holder_name','cc_number','cc_expires_month','cc_expires_year','cc_cvv_code','status','status_changed','status_description','affiliate_id','email_sent'
    ];

    
}

