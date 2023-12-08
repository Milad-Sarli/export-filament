<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer user_id
 * @property integer meeting_id
 * @property string role_in_meeting
 */
class MeetingUser extends Model
{
    protected $table="meeting_user";

    protected $guarded = [];
    //One To Many Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //One To Many Relationship with Meeting
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
