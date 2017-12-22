<?php 

namespace App\Http\Controllers;

use App\Leave;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveController extends Controller 
{
	public function index(){
		try{
			$leave=Leave::with([
			'employee' => function ($query) {
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
		return response()->json([$request->all()]);
		try{
			$leave = new Leave();
			$laave->from_date = $request->from_date;
			$laave->to_date = $request->to_date;
			$leave->save();
			return response()->json([
				'status' => 'ok',
				'data' => 'Record Added Successfully.'
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
