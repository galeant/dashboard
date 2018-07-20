<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $fillable = [
        'salutation',
        'email',
        'username',
        'fullname',
        'phone',
        'company_id'
    ];

    public function companies(){
        return $this->belongsTo('App\Models\Company', 'company_id', 'id');
    }
}
