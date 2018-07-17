<?php 
namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;
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
	protected $table = 'employees';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['email', 'username', 'password', 'fullname', 'phone', 'bod','address','avatar', 'status','remember_token'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token','deleted_at'];

    public static $rules = array(
        'email' => 'sometimes|required|email|max:191|unique:employees',
        'username' => 'unique:employees',
        'phone' => 'required'
    );
	use SoftDeletes;

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

}

