<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property string title
 * @property string description
 * @property integerr term_id
 */
class Committee extends Model
{

    protected $guarded = [];
    //One To Many Relationship with RoleUser
    public function committeeUsers()
    {
        return $this->hasMany(CommitteeUser::class);
    }

    //One To Many Relationship with RoleUser
    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    //One To Many Relationship with User
    public function term()
    {
        return $this->belongsTo(Term::class);
    }


    public static function getTotalData($order = 'asc'){
        $committees = Committee::with([
            'committeeUsers' => function ($query) {
                $query->with([
                    'user' => function ($query) {
                        $query->with([
                            'file' => function ($query) {
                                $query->select('id',DB::raw("CONCAT('files/',section,'/',type,'/',unique_id) as image"));
                            },
                        ]);
                    },
                ]);
            },
        ])
            ->orderBy('id',$order)
            ->get();
        $committees = addCodeToData($committees);

        return $committees;
    }

    public static function getActiveCommittees($order = 'asc'){
        $committees = Committee::with([
            'committeeUsers' => function ($query) {
                $query->with([
                    'user' => function ($query) {
                        $query->with([
                            'file' => function ($query) {
                                $query->select('id',DB::raw("CONCAT('files/',section,'/',type,'/',unique_id) as image"));
                            },
                        ]);
                    },
                ]);
            },
            'term'
        ])
            ->orderBy('order',$order)
            ->orderBy('id',$order)
            ->get();

        foreach ($committees as $key => $committee) {
            if (!$committee->term->is_active == 1){
                $committees->forget($key);
            }
        }

        $committees = addCodeToData($committees);

        return $committees;
    }

    public static function rules(){
        $rules = [
//            'title' => 'required|min:2|unique:committees,title',
            'title' => 'required|min:2',
            'description' => 'required|min:10'
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
            'title.required' => 'عنوان کمیسیون را وارد نمایید.',
            'title.min' => 'عنوان وارد شده باید حداقل 2 کاراکتر داشته باشه.',
//            'title.unique' => 'عنوان وارد شده تکراریست.',
            'description.required' => 'توضیحات کمیسیون را وارد نمایید.',
            'description.min' => 'توضیحات وارد شده باید حداقل 10 کاراکتر داشته باشه.',
        ];
        return $messages;
    }

}
