<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $fillable = ['code','uri','method','grouping','description'];

    public function rolePermission(){
        return $this->belongsToMany('App\Models\Permission', 'role_permissions', 'permission_id', 'role_id');
    }
}
