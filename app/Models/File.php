<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string section
 * @property string unique_id
 * @property string address
 * @property int type
 */
class File extends Model
{
    protected $guarded = [];
    //One To One Relationship with StoreCategory
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
