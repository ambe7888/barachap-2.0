<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class ProviderScheduleController extends Controller
{

    public function scheduleList(Request $request)
    {
        $providerId = Auth::guard('sanctum')->user()->id;
        $dayName = $request->query('day');

        $query = Schedule::where('provider_id', $providerId);


        if ($dayName) {
            $query->where('day', 'LIKE', '%' . $dayName . '%');
        }

        $schedules = $query->paginate(10);

        if ($schedules->isEmpty()){
            return response()->json([
                'message' => 'No schedules found',
            ]);
        }

        $response = [
            'schedules' => $schedules->items(),
            'pagination' => [
                'total' => $schedules->total(),
                'count' => $schedules->count(),
                'per_page' => $schedules->perPage(),
                'current_page' => $schedules->currentPage(),
                'last_page' => $schedules->lastPage(),
                'next_page' => $schedules->nextPageUrl(),
                'prev_page' => $schedules->previousPageUrl(),
            ]
        ];

        return response()->json($response);
    }


    public function scheduleCreate(Request $request){
        $provider_id = Auth::guard('sanctum')->user()->id;

        $request->validate([
            'day' => 'required',
            'schedule_for_all_days' => 'required',
            'schedule' => 'required',
        ]);

        if($request->has('schedule_for_all_days') && $request->schedule_for_all_days == 1){
            $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            foreach($days as $day){
                Schedule::create([
                    'day' => $day,
                    'provider_id' => $provider_id,
                    'schedule' => $request->schedule,
                    'status' => 0,
                    'allow_multiple_schedule' => $request->schedule_for_all_days,
                ]);
            }
            return response()->json(
                ["message" => __('Schedules added successfully for all days.')]
            );
        }else{
            Schedule::create([
                'day' => $request->day,
                'provider_id' => $provider_id,
                'schedule' => $request->schedule,
                'status' => 0,
                'allow_multiple_schedule' => 0,
            ]);
        }

        return response()->json([
            "message" => __('Schedule Added Success---')
        ]);
    }

    public function scheduleUpdate(Request $request)
    {

        $validatedData = $request->validate([
            'day' => 'required',
            'schedule' => 'required',
            'schedule_for_all_days' => 'required',
            'id' => 'required|integer',
        ]);

        $userId = Auth::guard('sanctum')->user()->id;
        $schedule = Schedule::where('id', $validatedData['id'])
            ->where('provider_id', $userId)
            ->first();

        if (!$schedule) {
            return response()->json([
                'error' => true,
                'message' => __('Schedule not found or you do not have permission to update it.'),
            ], 404);
        }

        // Update the schedule
        $schedule->update([
            'day' => $validatedData['day'],
            'schedule' => $validatedData['schedule'],
            'allow_multiple_schedule' => $validatedData['schedule_for_all_days'],
        ]);

        // Return a successful response
        return response()->json([
            'success' => true,
            'message' => __('Schedule updated successfully.'),
        ]);
    }

    public function scheduleDelete(Request $request){

        $validatedData = $request->validate([
            'id' => 'required',
        ]);

        $schedule = Schedule::find($validatedData['id']);

        if (!$schedule) {
            return response()->json([
                'message' => __('Schedule not found.'),
            ], 200);
        }

        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => __('Schedule deleted successfully.'),
        ], 200);
    }


}
