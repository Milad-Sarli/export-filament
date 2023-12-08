<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int meeting_no
 * @property string meeting_place
 * @property string meeting_date
 * @property string begin_time
 * @property string end_time
 * @property string meeting_type
 * @property string $subject
 * @property string $applicant_authority_id
 * @property int committee_id
 * @property integer meeting_boss_user_id
 */
class Meeting extends Model
{

    protected $guarded = [];
    //One To Many Relationship with RoleUser
    public function meetingUsers()
    {
        return $this->hasMany(MeetingUser::class);
    }

    //One To Many Relationship with User
    public function applicantAuthority()
    {
        return $this->belongsTo(User::class);
    }

    //One To Many Relationship with User
    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

    //One To Many Relationship with RoleUser
    public function agendas()
    {
        return $this->hasMany(Agenda::class);
    }

    public static function getTotalData($order = 'asc',$end_status = null){

        $where_clause = '1';
        if ($end_status == 'is_ended'){
            $where_clause .= ' and not isnull(meeting_ended_at)';
        } else if($end_status == 'not_ended'){
            $where_clause .= ' and isnull(meeting_ended_at)';
        }

        $meetings = Meeting::with('applicantAuthority')
            ->with('committee')
            ->orderBy('id',$order)
            ->whereRaw($where_clause)
            ->get();
        $meetings = addCodeToData($meetings);

        return $meetings;
    }

    public static function rules(){
        $rules = [
//            'title' => 'required|min:2|unique:committees,title',
            'subject' => 'required|min:2',
            'committee' => 'required',
            'meeting_place' => 'required|min:2',
            'meeting_date' => 'required',
            'begin_time' => 'required',
            'end_time' => 'required',
            'meeting_type' => 'required',
            'applicant_authority' => 'required',
        ];
        return $rules;
    }

    public static function rulesForUpdate(){
        $rules = [
            'title' => 'required|min:2',
            'description' => 'required|min:10'
        ];
        return $rules;
    }

    public static function messages(){
        $messages = [
            'subject.required' => 'علت دعوت را وارد نمایید',
            'subject.min' => 'طول متن علت دعوت باید حداقل 2 کاراکتر باشد',
            'committee.required' => 'کمیسیون را انتخاب نمایید',
            'meeting_place.required' => 'مکان جلسه را وارد نمایید',
            'meeting_place.min' => 'طول متن مکان جلسه باید حداقل 2 کاراکتر باشد',
            'meeting_date.required' => 'تاریخ جلسه را انتخاب نمایید',
            'begin_time.required' => 'زمان شروع جلسه را وارد نمایید',
            'end_time.required' => 'زمان پایان جلسه را وارد نمایید',
            'meeting_type.required' => 'نوع جلسه را تعیین نمایید',
            'applicant_authority.required' => 'مقام درخواست کننده را انتخاب نمایید',
        ];
        return $messages;
    }
}
