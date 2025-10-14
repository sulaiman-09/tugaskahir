<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles'; // tabel roles di database
    protected $fillable = ['name', 'guard_name', 'created_at', 'updated_at'];
    public $timestamps = true;

    // Relasi ke permissions
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id');
    }
}
