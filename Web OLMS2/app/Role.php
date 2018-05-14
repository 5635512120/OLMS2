<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kodeine\Acl\Traits\HasPermission;

class Role extends Model
{
    use HasPermission;

    protected $fillable = ['name', 'slug', 'description'];

    protected $table = 'roles';

    public function getRouteKeyName() {
        return 'slug';
    }
    
    public function users()
    {
        return $this->belongsToMany(config('auth.providers.users.model', config('auth.model')))->withTimestamps();
    }

    public function getPermissions()
    {
        return \Cache::remember(
            'acl.getPermissionsInheritedById_'.$this->id,
            config('acl.cacheMinutes'),
            function () {
                return $this->getPermissionsInherited();
            }
        );
    }

    public function can($permission, $operator = null, $mergePermissions = [])
    {
        $operator = is_null($operator) ? $this->parseOperator($permission) : $operator;

        $permission = $this->hasDelimiterToArray($permission);
        $permissions = $this->getPermissions() + $mergePermissions;

        // make permissions to dot notation.
        // create.user, delete.admin etc.
        $permissions = $this->toDotPermissions($permissions);

        // validate permissions array
        if ( is_array($permission) ) {

            if ( ! in_array($operator, ['and', 'or']) ) {
                $e = 'Invalid operator, available operators are "and", "or".';
                throw new \InvalidArgumentException($e);
            }

            $call = 'canWith' . ucwords($operator);

            return $this->$call($permission, $permissions);
        }

        // validate single permission
        return isset($permissions[$permission]) && $permissions[$permission] == true;
    }

    protected function canWithAnd($permission, $permissions)
    {
        foreach ($permission as $check) {
            if ( ! in_array($check, $permissions) || ! isset($permissions[$check]) || $permissions[$check] != true ) {
                return false;
            }
        }

        return true;
    }

    protected function canWithOr($permission, $permissions)
    {
        foreach ($permission as $check) {
            if ( in_array($check, $permissions) && isset($permissions[$check]) && $permissions[$check] == true ) {
                return true;
            }
        }

        return false;
    }
}
