<?php 

namespace App\Http\Controllers;

use JWTAuth;
use App\Leave;
use App\Leave_sub;
use App\Employee;
use App\Department;
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

	public function leaveApproved(Request $request){
		$requestData = json_decode($request->id, true);
		try{
			$leave=Leave::with([
				'user' => function ($query) {
					$query->select('id', 'name');
				},
				'leave_days' => function($query){
					$query->select('*')->where('leave_approve_status', null);
				},
			])->get();
			foreach ($leave as $leaveData) {
				$leaveData['employee'] = $leaveData->user->employee;
				$leaveData['department'] = Department::join('employees', function($join){
						$join->on('departments.id', '=', 'employees.department_id');
			 		})->where('employees.department_id', '=', $leaveData->user->employee['department_id'])->first(['departments.id', 'departments.name']);
				unset($leaveData->user->employee);
			}
			if ($requestData==null) {
				return response()->json([
					'status' => 'ok',
					'data' => $leave
				], 200);
			}else{
				foreach ($requestData as $key => $value) {
					Leave_sub::where('id', $key)
					->update(['leave_approve_status' => $value]);
				}
				return response()->json([
					'status' => 'ok',
					'data' => 'Leave allowed / denied.'
				], 200);	
			}
		}catch(\Exception $ex){
			dd($ex);
			return response()->json([
				'status' => 'error',
				'data' => 'Something Went Wrong.'
			], 200);
		}
		
	}
}
