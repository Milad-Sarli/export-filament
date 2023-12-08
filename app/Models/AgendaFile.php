<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgendaFile extends Model
{
    protected $guarded = [];
    //One To One Relationship with File
    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }

    //One To One Relationship with File
    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
