<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProductType extends Model
{
    protected $table = 'company_product_types';
    protected $fillable = [
        'company_id',
        'product_type_id',
        'created_at',
        'updated_at'
    ];
}
