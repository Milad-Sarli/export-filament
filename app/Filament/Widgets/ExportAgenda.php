<?php

namespace App\Filament\Widgets;

use App\Models\Agenda;
use App\Models\Committee;
use App\Models\Meeting;
use App\Models\Term;
use App\Models\User;
use Ariaieboy\FilamentJalaliDatetimepicker\Forms\Components\JalaliDatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;

class ExportAgenda extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(Agenda::query()->with(['meeting' => function ($qu) {
                return $qu->with('committee', function ($q) {
                    return $q->with('term');
                });
            }, 'votes' => function ($qu) {
                return $qu->with('user');
            }]))
            ->columns([
                //                TextColumn::make('meeting.subject')->label('کموسیون جلسه')->getStateUsing(function ($record) {
                //                    return new HtmlString("<p class='truncate overflow-hidden' style='max-width: 300px'> $record->subject </p>");
                //                })->tooltip(fn ($record) => $record->subject)->searchable(),
                Tables\Columns\TextColumn::make('description')->label('متن مصوبه')->getStateUsing(function ($record) {
                    $cleanString = strip_tags($record->description);
                    return new HtmlString("<p class='truncate overflow-hidden' style='max-width: 300px'>$cleanString</p>");
                })->tooltip(fn ($record) => strip_tags($record->description))->html()->weight('bold')->searchable(true, null, true, false),
                Tables\Columns\TextColumn::make('result')->getStateUsing(function ($record) {
                    if ($record->result === 'accepted') {
                        return new HtmlString('<p style="background: rgba(190,255,95,0.18);color: #00ba00" class="px-3 font-bold rounded-full"> مصوب شده </p>');
                    } else if ($record->result === 'rejected') {
                        return new HtmlString('<p style="background: rgba(255,95,167,0.18);color: #ba0063" class="px-3 font-bold rounded-full"> رد شده </p>');
                    } else {
                        return new HtmlString('<p class="px-3 font-bold rounded-full"> در انتظار </p>');
                    }
                })->html()->label('نتیجه'),
                Tables\Columns\TextColumn::make('meeting.committee.title')->wrap()->badge()->color('success')->label('کموسیون')->searchable(false, null, true, false),
                // TextColumn::make('votes.user')->getStateUsing(function ($record) {
                //     $names = "";
                //     foreach ($record->votes as $com) {
                //         $names .= $com->user->first_name . " " . $com->user->last_name . "-";
                //     }
                //     return new HtmlString("<p class='truncate overflow-hidden' style='max-width: 300px !important;'>$names</p>");
                // })->wrap()->weight('bold')->color('info')->label('اعضای جلسه'),
                Tables\Columns\TextColumn::make('meeting.committee.term.title')->wrap()->badge()->color('primary')->label('دوره'),
                // ->searchable(['title'], null, true, false),
                Tables\Columns\TextColumn::make('meeting.meeting_no')->sortable()->wrap()->weight('bold')->label('شماره جلسه')->searchable(['meeting_no'], null, true, false),
                Tables\Columns\TextColumn::make('start_time')->sortable()->getStateUsing(fn ($record) => verta($record->start_time)->format('Y/m/d'))->label('تاریخ جلسه'),
                Tables\Columns\TextColumn::make('meeting.meeting_place')->label('مکان جلسه')->searchable(false, null, true, false),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('start_time')->firstDayOfWeek(6)->jalali()->label('از تاریخ')->columnSpan(3),
                        \Filament\Forms\Components\DatePicker::make('end_time')->firstDayOfWeek(6)->jalali()->label('تا تاریخ')->columnSpan(3),
                        Select::make('term')->preload()->searchable()->label('دوره')->options(Term::all()->pluck('title', 'id'))->columnSpan(3),
                        Select::make('users')->label('اعضاء')->preload()->searchable()->options(function () {
                            $users = User::all();
                            $result = array();
                            foreach ($users as $user) {
                                $result[$user->id] = $user->first_name . ' ' . $user->last_name;
                            }
                            return $result;
                        })->placeholder('انتخاب')->multiple()->columnSpan(3),
                        Select::make('committee')->placeholder('انتخاب')->preload()->searchable()->label('کموسیون')->options(Committee::all()->pluck('title', 'id'))->columnSpan(4),
                        Select::make('result')->placeholder('انتخاب')->preload()->searchable()->label('نتیجه')->options(['accepted' => 'مصوب شده', 'rejected' => 'رد شده', "pend" => 'در انتظار'])->columnSpan(4),
                        Select::make('place')->preload()->searchable()->label('مکان جلسه')->options(Meeting::select('meeting_place')->distinct()->get()->pluck('meeting_place', 'id'))->columnSpan(4),
                    ])->columnSpanFull()
                    ->columns(12)
                    ->query(function ($query, $data) {
                        return $query->when($data['users'], function ($q) use ($data) {
                            return $q->whereHas('votes', function ($qu) use ($data) {
                                return $qu->whereHas('user', function ($que) use ($data) {
                                    return $que->whereIn('id', $data['users']);
                                });
                            });
                        })->when($data['start_time'], function ($q) use ($data) {
                            return $q->whereDate('start_time', $data['start_time']);
                        })->when($data['committee'], function ($q) use ($data) {
                            return $q->whereHas('meeting', function ($qu) use ($data) {
                                return $qu->whereHas('committee', function ($que) use ($data) {
                                    return $que->where('id', $data['committee']);
                                });
                            });
                        })->when($data['result'], function ($query) use ($data) {
                            if ($data['result'] === 'pend') {
                                return $query->where('result', null);
                            } else {
                                return $query->where('result', $data['result']);
                            }
                        });
                    })
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ])
            ->striped();
    }
}
