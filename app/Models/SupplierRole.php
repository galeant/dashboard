<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierRole extends Model
{
    protected $table = 'supplier_roles';
    public function suppliers(){
        return $this->hasMany('App\Models\Supplier');
    }
}
