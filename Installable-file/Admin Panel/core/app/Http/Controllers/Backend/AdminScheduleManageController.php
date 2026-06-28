<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminScheduleManageController extends Controller
{
    public function add_schedule(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'day' => 'required',
                'schedule_for_all_days' => 'nullable',
                'schedule' => 'required',
            ]);

            $admin_id = Auth::guard('admin')->user()->id;
            if($request->has('schedule_for_all_days') && $request->schedule_for_all_days == 1){
                $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                foreach($days as $day){
                    Schedule::create([
                        'day' => $day,
                        'admin_id' => $admin_id,
                        'schedule' => $request->schedule,
                        'status' => 0,
                        'allow_multiple_schedule' => $request->schedule_for_all_days ?? 0,
                    ]);
                }
            }else{
                Schedule::create([
                    'day' => $request->day,
                    'admin_id' => $admin_id,
                    'schedule' => $request->schedule,
                    'status' => 0,
                    'allow_multiple_schedule' => 0,
                ]);
            }

            return back()->with(FlashMsg::item_new(__('Schedule Successfully Created')));
        }
        return view('backend.pages.schedules.add-schedule');
    }
    //all user
    public function all_schedules()
    {
        $admin_id = Auth::guard('admin')->id();
        $schedules = Schedule::whereNotNull('admin_id')
            ->where('admin_id', $admin_id)
            ->latest()
            ->paginate(10);

        return view('backend.pages.schedules.all-schedules',compact('schedules'));
    }


    // user pagination
    function schedule_paginate(Request $request)
    {
        if($request->ajax()){
            $admin_id = Auth::guard('admin')->id();
            $schedules = Schedule::whereNotNull('admin_id')
                ->where('admin_id', $admin_id)
                ->latest()
                ->paginate(10);
            return view('backend.pages.schedules.search-result',compact('schedules'));
        }
    }


    // search user
    public function search_schedule(Request $request)
    {
        $admin_id = Auth::guard('admin')->id();
        $schedules= Schedule::whereNotNull('admin_id')
            ->where('admin_id', $admin_id)->where(function($q) use($request){
                $q->where('day', 'LIKE', "%". strip_tags($request->string_search) ."%");
            })->paginate(10);

        return $schedules->total() >= 1 ? view('backend.pages.schedules.search-result', compact('schedules'))
            ->render() : response()
            ->json(['status'=>__('nothing')]);
    }

    public function edit_schedule(Request $request)
    {
        $admin_id = Auth::guard('admin')->id();
        $validatedData = $request->validate([
            'edit_id' => 'required',
            'edit_day' => 'required',
            'edit_schedule' => 'required',
        ]);

        $schedule = Schedule::where('id', $validatedData['edit_id'])
            ->where('admin_id', $admin_id)
            ->first();

        if (!$schedule) {
            FlashMsg::item_new(__('Schedule not found'));
            return back();
        }

        // Update the schedule
        $schedule->update([
            'day' => $validatedData['edit_day'],
            'schedule' => $validatedData['edit_schedule'],
        ]);

        FlashMsg::item_new(__('Schedule updated successfully'));
        return back();
    }

    public function scheduleDelete($id){
        try {
            $schedule = Schedule::findOrFail($id);
            $schedule->delete();
            return redirect()->back()->with(FlashMsg::item_delete(__('schedule Deleted Success')));
        }catch (\Exception $e) {
            return redirect()->back()->with('error', __('An error occurred while deleting the service.'));
        }
    }
}
