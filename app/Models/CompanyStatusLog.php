<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyStatusLog extends Model {
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
    protected $table = 'company_status_logs';
    protected $fillable = ['company_id','status','note'];
}
