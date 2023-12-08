<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

/**
 * @property string first_name
 * @property string last_name
 * @property string title
 * @property string national_code
 * @property string role_in_meeting
 * @property string role_in_committee
 * @property string code
 * @property string image
 * @property bool user_is_online
 * @property integer user_code
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];
    protected $hidden = ['password', 'remember_token'];

    public static function getTotalData($order = 'asc'){
        $users = User::with([
            'file' => function ($query) {
                $query->select('id',DB::raw("CONCAT('files/',section,'/',type,'/',unique_id) as image"));
            },
            'signature_file' => function ($query) {
                $query->select('id',DB::raw("CONCAT('files/',section,'/',type,'/',unique_id) as signature"));
            },
        ])
            ->orderBy('id',$order)
            ->get();

        foreach ($users as $user) {
            $user->user_is_online = userIsOnline($user->id);
        }

        $users = addCodeToData($users);

        return $users;
    }

    public static function getUsersOfCurrentTerm($order = 'asc'){
        $users = User::with([
            'file' => function ($query) {
                $query->select('id',DB::raw("CONCAT('files/',section,'/',type,'/',unique_id) as image"));
            },
            'signature_file' => function ($query) {
                $query->select('id',DB::raw("CONCAT('files/',section,'/',type,'/',unique_id) as signature"));
            },
        ])
            ->orderBy('order')
            ->orderBy('id',$order)
            ->whereIn('id',getUserIdsOfCurrentTerm())
            ->get();

        foreach ($users as $user) {
            $user->user_is_online = userIsOnline($user->id);
        }

        $users = addCodeToData($users);

        return $users;
    }

    public static function getUsersOfAllTerms($order = 'asc'){
        $users = User::with([
            'file' => function ($query) {
                $query->select('id',DB::raw("CONCAT('files/',section,'/',type,'/',unique_id) as image"));
            },
            'signature_file' => function ($query) {
                $query->select('id',DB::raw("CONCAT('files/',section,'/',type,'/',unique_id) as signature"));
            },
        ])
            ->orderBy('order')
            ->orderBy('id',$order)
            ->whereIn('id',getUserIdsOfAllTerms())
            ->get();

        foreach ($users as $user) {
            $user->user_is_online = userIsOnline($user->id);
        }

        $users = addCodeToData($users);

        return $users;
    }

    public function getFullNameWithTitle()
    {
        return "{$this->first_name} {$this->last_name} ({$this->title})";
    }

    public function getFullNameWithoutTitle()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    //One To Many Relationship with RoleUser
    public function roles()
    {
        return $this->hasMany(RoleUser::class);
    }

    //One To Many Relationship with RoleUser
    public function meetingsUsers()
    {
        return $this->hasMany(MeetingUser::class);
    }

    //One To One Relationship with File
    public function file()
    {
        return $this->belongsTo(File::class);
    }

    //One To One Relationship with File
    public function signature_file()
    {
        return $this->belongsTo(File::class);
    }

    //One To Many Relationship with RoleUser
    public function votes()
    {
        return $this->hasMany(AgendaUserVote::class);
    }


    // -----------------------------------------------------------------------------------------------------
    // custom methods
    // -----------------------------------------------------------------------------------------------------

    public function attachRole($roleName)
    {
        $role = Role::where('name',$roleName)->first();

        $roleUser = new RoleUser();
        $roleUser->user_id = $this->id;
        $roleUser->role_id = $role->id;

        $roleUser->save();

        return $roleUser->id;
    }

    public function hasRole($roleName)
    {
        $role = Role::where('name',$roleName)->first();

        $roleUser = RoleUser::where('user_id',$this->id)->where('role_id',$role->id)->first();

        if ($roleUser){
            return true;
        } else {
            return false;
        }
    }

    public static function rulesForUpdate($user_id=null){
        $rules = [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
//            'title' => 'required',
//            'username' => 'required',
//            'email' => 'required',
//            'mobile' => 'required|min:11|unique:users,mobile,'.$user_id,
//            'gender' => 'required',
        ];
        return $rules;
    }

    public static function messages(){
        $messages = [
            'first_name.required' => 'نام را وارد نمایید',
            'first_name.min' => 'نام باید حداقل 2 کاراکتر باشد',
            'last_name.required' => 'نام خانوادگی را وارد نمایید',
            'last_name.min' => 'نام خانوادگی باید حداقل 2 کاراکتر باشد',
            'title.required' => 'عنوان را وارد نمایید',
            'username.required' => 'نام کاربری را وارد نمایید',
            'username.min' => 'نام کاربری باید حداقل 3 کاراکتر باشد',
            'email.required' => 'ایمیل را وارد نمایید',
            'mobile.required' => 'موبایل را وارد نمایید',
            'mobile.unique' => 'موبایل وارد شده تکراریست',
            'mobile.min' => 'موبایل باید به صورت 11 رقمی وارد شود',
            'gender.required' => 'جنسیت را انتخاب نمایید',
        ];
        return $messages;
    }
}
