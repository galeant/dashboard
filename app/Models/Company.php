<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model {
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    /**
     * The database table used by the model.
     *
     * @var stringA
     */
    protected $table = 'companies';
    protected $fillable = [
        'company_name',
        'company_phone',
        'company_email',
        'company_web',
        'company_address',
        'company_postal',
        'book_system',
        'bank_name',
        'bank_account_number',
        'bank_acount_title',
        'bank_account_name',
        'bank_account_scan_path',
        'company_ownership',
        'akta_path',
        'siup_path',
        'npwp_path',
        'ktp_path',
        'evidance_path',
        'status',
        'province_id',
        'city_id'
    ];

    public function suppliers(){
        return $this->hasMany('App\Models\Supplier');
    }
    public function log_statuses(){
        return $this->hasMany('App\Models\CompanyStatusLog','company_id','id');
    }
    public function pic(){
        return $this->hasOne('App\Models\Supplier')->orderBy('created_at','ASC');
    }
}

