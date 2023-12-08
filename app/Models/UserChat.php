<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserChat extends Model
{
    //One To Many Relationship with User
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //One To Many Relationship with User
    public function recipient()
    {
        return $this->belongsTo(User::class);
    }
}
