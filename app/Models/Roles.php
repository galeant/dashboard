<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';
    protected $fillable = ['code','description'];

    public function rolePermission(){
        return $this->belongsToMany('App\Models\Permission', 'role_permissions', 'role_id', 'permission_id');
    }
    public function adminRoles(){
        return $this->belongsToMany('App\Models\Employee', 'admin_role', 'role_id', 'admin_id');
    }
}   
