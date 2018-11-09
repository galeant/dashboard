<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyLevelCommission extends Model
{
    protected $table = 'company_level_commisions';
    protected $fillable = [
        'company_level_id',
        'percentage',
        'product_type',
        'product_type_code'
    ];
}
