<?php

namespace App\Http\Controllers;

use App\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendanceData = Attendance::all();
        return response()->json([
            'status'=>'ok',
            'msg'=>$attendanceData
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $user_id = \JWTAuth::toUser($request->token)->id;
            
            $attendanceData = new Attendance;
            $attendanceData->employee_id = $user_id;
            $attendanceData->start_time = date("H:i:s");
            $attendanceData->date = date("Y-m-d");

            $attendance = Attendance::where([
                ['date', date("Y-m-d")], 
                ['employee_id', $user_id]
            ])->count();
            if($attendance > 0){
                $attendance_id = Attendance::where([
                    ['date', date("Y-m-d")], 
                    ['employee_id', $user_id], 
                    ['end_time',NULL]
                ])->max('id');
                if($attendance_id != NULL){
                    $attendanceData = Attendance::find($attendance_id);
                    // time diffrence 
                    $to = \Carbon\Carbon::createFromFormat('H:i:s', $attendanceData->start_time);
                    $from = \Carbon\Carbon::createFromFormat('H:i:s', date("H:i:s"));
                    $diff_in_minutes = $to->diffInMinutes($from);
                    
                    $attendanceData->session_time = $diff_in_minutes;
                    $attendanceData->end_time = date("H:i:s");
                    $attendanceData->save();
                }else
                    $attendanceData->save();
            }else
                $attendanceData->save();
                return response()->json([
                    'status'=>'ok',
                    'msg'=>"Success",
                    'attedance'=>$attendanceData
                ]);
        }catch(\Excaption $ex){
            return response()->json([
                'status'=>'error',
                'msg'=>$ex    
            ]);
        }
    }

    public function inoutflag(Request $request){
        $user_id = \JWTAuth::toUser($request->token)->id;
        $attendance = Attendance::where([
            ['date', date("Y-m-d")], 
            ['employee_id', $user_id]
        ])->get();
        $attendanceCount = $attendance->count();
        if($attendanceCount > 0){
            $attendance_id = Attendance::where([
                ['date', date("Y-m-d")], 
                ['employee_id', $user_id], 
                ['end_time',NULL]
            ])->max('id');
            $last_session = array_last($attendance->toArray());
            if($attendance_id > 0){
                return response()->json([
                    'status'=>'ok',
                    'msg'=>'out',
                    'last_session'=>$last_session
                ]);
            }else{
                return response()->json([
                    'status'=>'ok',
                    'msg'=>'in',
                    'last_session'=>$last_session
                ]);
            }
        }else{
            $lastDayAttendance = Attendance::where([
                ['employee_id', $user_id]
            ])->orderBy('id', 'desc')->offset(1)->limit(1)->get();
            return response()->json([
                'status'=>'ok',
                'msg'=>'in',
                'previous_entry'=>$lastDayAttendance
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
