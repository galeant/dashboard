<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    public static function list()
    {
        return DB::table('transactions as a')
                ->select('a.id','a.user_id','b.email',DB::raw("CONCAT(`b`.`firstname`,' ',`b`.`lastname`) as fullname"),'a.coupon_id','a.coupon_code','a.transaction_number','a.total_discount','a.total_price','a.total_paid','a.payment_method','a.paid_at','c.name as status','c.color as status_color','a.created_at')
                ->leftJoin('users as b','b.id','=','a.user_id')
                ->join('transaction_status as c','a.status_id','=','c.id');
    }

    public function transaction_status()
	{
	    return $this->hasOne('App\Models\TransactionStatus','id','status_id');
    }

    public function customer()
    {
        return $this->hasOne('App\Models\Members','id','user_id');
    }

    public function transaction_log_status()
    {
        return $this->hasMany('App\Models\TransactionLogStatus', 'transaction_id','id')->orderBy('created_at','DESC');
    }

    public function booking_tours()
    {
        return $this->hasMany('App\Models\BookingTour', 'transaction_id','id');
    }
    public function booking_hotels()
    {
        return $this->hasMany('App\Models\BookingHotel', 'transaction_id','id');
    }
    public function booking_activities()
    {
        return $this->hasMany('App\Models\BookingActivity', 'transaction_id','id');
    }
    public function booking_car_rent()
    {
        return $this->hasMany('App\Models\BookingCarRental', 'transaction_id','id');
    }
    // TRAVELLER
    public function contact_list()
    {
        return $this->belongsToMany('App\Models\MemberContact','user_contact_transaction','transaction_id','user_contact_id');
    }
    // USER
    public function user()
    {
        return $this->belongsTo('App\Models\Members','user_id','id');
    }
    // CONTACT
    public function contact()
    {
        return $this->belongsTo('App\Models\Members','user_contact_id','id');
    }
}
