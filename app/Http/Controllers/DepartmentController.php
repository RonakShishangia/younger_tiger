<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $department = Department::all();
        return response()->json([
            'status'=>true,
            'data'=>$department
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
            $department = new Department($request->all());
            $department->save();    
            return response()->json([
                'status' => 'ok',
                'data' => ucfirst($request->name)." Department Added Successfully."
            ],200);
		} catch(\Exception $ex){
			$msg = "Department Controller : Database error.";
			if(env('APP_DEBUG')) $msg = $ex->getMessage();
			return response()->json([
				'status' => 'error',
				'data' => array('msg' => $msg)
			], 400);
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        try{
            return response()->json([
                'status' => "ok",
                "data" => $department
            ]);
        }catch(\exception $ex){
            return response()->json([
                'status' => "error",
                "data" => ""
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        try{
            return response()->json([
                'status' => "ok",
                'data' => $department
            ]);
        }catch(\Exception $ex){
            return response()->json([
                'status' => "error",
                'data' => "Something Went Wrong."
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        try{
            $department->update($request->all());
            return response()->json([
                'status'=> "ok",
                "data"=>"Successfully Updated."
            ]);
        }catch(\Exception $ex){
            return response()->json([
                'status'=> "errot",
                "data"=>"Something Went Wrong."
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        try{
            $department->delete();
            return response()->json([
                'status'=>'ok',
                'data'=>"",
                'msg'=>"Successfully Deleted."
            ]);
        }catch(\Exception $ex){
            return response()->json([
                'status'=>'error',
                'data'=>"",
                'msg'=>"Something Went Wrong."
            ]);
        }
    }
}
