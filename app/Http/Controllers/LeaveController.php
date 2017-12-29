<?php 

namespace App\Http\Controllers;

use JWTAuth;
use App\Leave;
use App\Leave_sub;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveController extends Controller 
{
	public function index(Request $request){
		try{
			$user_id = JWTAuth::toUser($request->token)->id;
			$leave=Leave::where('user_id', $user_id)->with([
				'user' => function ($query) {
					$query->select('id', 'name');
				},
				'leave_days' => function($query){
					$query->select('*');
				},
			])->get();
		return response()->json([
				'status' => 'ok',
				'data' => $leave
			], 200);
		} catch(\Exception $ex){
			dd($ex);
			return response()->json([
				'status' => 'error',
				'data' => 'Something Went Wrong.'
			], 200);
		}
	}

	public function store(Request $request){
		$requestData = json_decode($request->date, true);
		try{
			$leaves = new Leave();
			$leaves->from_date = $request->from_date;
			$leaves->to_date = $request->to_date;
			$leaves->user_id = $request->user_id;
			$leaves->reason = $request->reason;
			$leaves->save();
			foreach($requestData as $key=>$value){
				$leave_sub = new Leave_sub();
				$leave_sub->leave_id = $leaves->id;
				$leave_sub->leave_date =$value['leave_date'];
				$leave_sub->half_day = $value['leave_type']=="half" ? $value['half_day'] : null;
				$leave_sub->save();
			}
			return response()->json([
				'status' => 'ok',
				'data' => 'Leave created successfully,'
			], 200);
		} catch(\Exception $ex){
			return response()->json([
				'status' => 'error',
				'data' => 'Something Went Wrong.'
			], 200);
		}
	}

	public function edit($id) {
		try{
			$leave = Leave::whereId($id)->first();
			$leaveDays = Leave_sub::where('leave_id', $id)->get();
			return response()->json([
				'status' => 'ok',
				'data' => compact('leave', 'leaveDays')
			], 200);
		} catch(\Exception $ex){
			return response()->json([
				'status' => 'error',
				'data' => 'Something Went Wrong.'
			], 200);
		}
	}
	public function update(Request $request, $id){
		try{
			$leave = Leave::whereId($id)->first();
			$leave->user_id= $request->user_id;
			$leave->from_date= $request->from_date;
			$leave->to_date= $request->to_date;
			$leave->reason= $request->reason;			
			$leave->save();
			$requestData = json_decode($request->date, true);
			leave_sub::where('leave_id', $id)->forceDelete();
			foreach($requestData as $key=>$value){
				$leave_sub = new Leave_sub();
				$leave_sub->leave_id = $id;
				$leave_sub->leave_date =$value['leave_date'];
				$leave_sub->half_day = $value['leave_type']=="half" ? $value['half_day'] : null;
				$leave_sub->save();
			}
			return response()->json([
				'status' => 'ok',
				'data' => 'Success - Record Updated Successfully.'
			], 200);
		}catch(\Exception $ex){
			dd($ex);
			return response()->json([
				'status' => 'error',
				'data' => 'Something Went Wrong.'
			], 200);
		}
	}

	public function destroy($id) {
		$ids = explode(',', $id);
		try{
			$del_leave_sub = leave_sub::whereIn('leave_id', $ids)->forceDelete();
			$leave = Leave::whereIn('id',$ids)->delete();
			return response()->json([
				'status' => 'ok',
				'data' => 'Record Deleted Successfully.'
			], 200);
		}catch(\Exception $ex){
			return response()->json([
				'status' => 'error',
				'data' => 'Something Went Wrong.'
			], 200);
		}
	}
}
