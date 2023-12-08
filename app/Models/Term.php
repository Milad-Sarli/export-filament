<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{

    protected $guarded = [];
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    //One To Many Relationship with RoleUser
    public function committees()
    {
        return $this->hasMany(Committee::class);
    }
}
