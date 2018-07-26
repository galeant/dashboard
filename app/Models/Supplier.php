<?php 
namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Supplier extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;
	/**
	 * The database table used by the model.
	 *
	 * @var stringA
	 */
	protected $table = 'suppliers';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['email', 'username', 'password', 'fullname', 'role_id', 'status','company_id', 'status','token','phone'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'token','deleted_at'];

    public static $rules = array(
        'email' => 'sometimes|required|email|max:191|unique:suppliers',
        'username' => 'unique:suppliers',
        'phone' => 'required'
    );

    // public function Roles()
    // {
    //     return $this->belongsToMany('App\Role', 'admin_roles', 'admin_id', 'role_id');
    // }

    // public function Acl() {
    // 	$effectivePermissions = array();
    // 	foreach ($this->Roles()->get() as $role) {
    // 		$permissionsViaRoles = $role->Permissions()->pluck('code')->toArray();
    // 		$effectivePermissions = array_merge($effectivePermissions, $permissionsViaRoles);
    // 	}
    // 	$effectivePermissions = array_merge($effectivePermissions);
    // 	return $effectivePermissions;
    // }
    
    // public function HasPermission($permission) {
    // 	return in_array($permission, $this->Acl());
    // }

    public function companies()
    {
        return $this->belongsTo('App\Models\Company', 'company_id','id');
    }
    public function supplier_roles()
    {
        return $this->belongsTo('App\Models\SupplierRole', 'role_id','id');
    }
}

