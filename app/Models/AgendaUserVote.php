<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer user_id
 * @property integer agenda_id
 * @property string vote
 */
class AgendaUserVote extends Model
{

    protected $guarded = [];
    //One To Many Relationship with User
    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }

    //One To Many Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
