<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer meeting_id
 * @property string summary
 * @property string description
 * @property string rules
 * @property int order
 * @property int $is_two_urgencies
 */
class Agenda extends Model
{
    protected $guarded = [];
    //One To Many Relationship with Meeting
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    //One To Many Relationship with RoleUser
    public function votes()
    {
        return $this->hasMany(AgendaUserVote::class);
    }

    //One To Many Relationship with RoleUser
    public function files()
    {
        return $this->hasMany(AgendaFile::class);
    }

    //One To Many Relationship with RoleUser
    public function reportFiles()
    {
        return $this->hasMany(AgendaReportFile::class);
    }



    public static function getTotalData($order = 'asc'){
        $agendas = Agenda::with('meeting')->orderBy('order',$order)->get();
        $agendas = addCodeToData($agendas);

        return $agendas;
    }
}
