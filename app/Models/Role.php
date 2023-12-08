<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];
    //One To Many Relationship with RoleUser
    public function userRoles()
    {
        return $this->hasMany(RoleUser::class);
    }
}
