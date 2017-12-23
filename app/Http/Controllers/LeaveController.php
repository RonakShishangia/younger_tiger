<?php 

namespace App\Http\Controllers;

use App\Leave;
use App\Leave_sub;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveController extends Controller 
{
	public function index(){
		try{
			$leave=Leave::with([
			'user' => function ($query) {
				$query->select('id', 'name');
			},
		])->get();
		return response()->json([
				'status' => 'ok',
				'data' => $leave
			], 200);
		} catch(\Exception $ex){
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
			return response()->json([
				'status' => 'ok',
				'data' => $leave
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
			$leave->update($request->all());
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
			$leave = Leave::whareIn('id',$ids)->delete();
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

	public function get(){
		try {
			$leaves = Leave::select('id','employee_id', 'date', 'half_day')->get(); 
			return response()->json([
				'status' => 'ok',
				'data' => $leaves
			], 200);
		} catch(\Exception $ex){
			return response()->json([
				'status' => 'error',
				'data' => 'Something Went Wrong.'
			], 200);
		}
	}

}
