<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer committee_id
 * @property integer user_id
 * @property string role_in_committee
 */
class CommitteeUser extends Model
{
    protected $table="committee_user";

    protected $guarded = [];
    //One To Many Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class)->orderBy('order');
    }

    //One To Many Relationship with Committee
    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }
}
