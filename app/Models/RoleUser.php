<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer user_id
 * @property integer role_id
 */
class RoleUser extends Model
{

    protected $guarded = [];
    protected $table="role_user";

    //One To Many Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //One To Many Relationship with Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
