<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionMenu extends Model
{
    protected $table = 'permissions';
    protected $fillable = ['name', 'guard_name', 'created_at', 'updated_at'];
    public $timestamps = true;

    public function roles()
    {
        // Pakai tabel pivot yang sama dengan model lama
        return $this->belongsToMany(\App\Models\Role::class, 'role_has_permissions', 'permission_id', 'role_id');
    }
    
}
