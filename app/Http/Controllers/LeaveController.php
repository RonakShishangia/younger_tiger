<?php 

namespace App\Http\Controllers;

use JWTAuth;
use App\Leave;
use App\Leave_sub;
use App\Employee;
use App\Department;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use Pusher\Pusher;

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
				$leaveData['emp_avatar'] = asset('/uploads/employee_image/'.$leaveData['employee']['avatar']);
				$leaveData['department'] = Department::where('id', '=', $leaveData['employee']['department_id'])->first();
				// $leaveData['department'] = Department::join('employees', function($join){
				// 		$join->on('departments.id', '=', 'employees.department_id');
			 	// 	})->where('employees.department_id', '=', $leaveData->user->employee['department_id'])->first(['departments.id', 'departments.name']);
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

			/*
			 * FCM notification for WEB
			 * START
			 */ 
				$options = array(
					'cluster' => 'ap2',
					'encrypted' => true
				);
				$pusher = new Pusher(
					'2c49ce3a30ceb17d9fcd',
					'5ba33fd853b6ae3862b1',
					'450486',
					$options
				);

				$data['message'] = 'hello world';
				$pusher->trigger('my-channel', 'my-event', $data);

			/*
			* FCM notification for Android
			* START
			*/ 
			$optionBuilder = new OptionsBuilder();
			$optionBuilder->setTimeToLive(60*20);

			$notificationBuilder = new PayloadNotificationBuilder('my title');
			$notificationBuilder->setBody("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.")->setIcon("")->setSound('default');

			$dataBuilder = new PayloadDataBuilder();
			$dataBuilder->addData(['a_data' => 'my_data']);

			$option = $optionBuilder->build();
			$notification = $notificationBuilder->build();
			$data = $dataBuilder->build();

			//Get FCM device id by leave request user
			$FCMDeviceId = \App\User::find($request->userId);
			$device_ID = $FCMDeviceId->FCM_device_id;
			$downstreamResponse = FCM::sendTo($device_ID, $option, $notification, $data);
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
