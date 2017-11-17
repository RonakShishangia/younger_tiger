<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::all();
        foreach($employees as $employeeData){
            $employeeData['avatar'] = asset('/uploads/employee_image/'.$employeeData['avatar']);
        }
        return response()->json([
            'status'=>true,
            'data'=>$employees
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
        //dd($request->all());
        if ($request->isMethod('post')){
            
            /*
            * Simple direct fole upload
            * For File Upload
            * 

            $emp_avatar='';
            if($request->hasFile($request->input('avatar'))){
                $dir = public_path('/uploads/employee_image');
                $emp_avatar=time().'.'.$request->file('avatar')->getClientOriginalExtension();
                $NewFilePath=$request->file('avatar')->move($dir, $emp_avatar);
                if(!file_exists(public_path('/uploads/employee_image/'.$emp_avatar))) {
                    return response()->json([
                        'status'=>false,
                        'message'=>'Problame in file upload',
                        'data'=>''
                    ]);
                }
            }
            
            */

            /*
            * Simple direct fole upload
            * For File Upload
            * base64 to image
            * */
            /// The final filename.
            $fileName= str_replace(".","",microtime(true)).'.png';//Input::get('file_name');
            /// Upload path
            $dir = public_path('/uploads/employee_image/');
            $uploadPath = $dir . $fileName;
            //dd($uploadPath);
            /// Decode your image/file
            $data = base64_decode($request->avatar);
            /// Upload the decoded file/image
            if(!file_put_contents($uploadPath , $data)){
                return response()->json([
                    'status'=>false,
                    'message'=>'Unable to save file',
                ]);
            }

            $employeeData = new Employee;
            
            $employeeData1= $request->all();
            $employeeData1['avatar'] = $fileName; 
            unset($employeeData1['token']);
            $employeeData->create($employeeData1);

            return response()->json([
                'status'=>true,
                'message'=>'Employee created successfully',
                'data'=>$employeeData1
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        return response()->json([
            'status'=>true,
            'data'=>$employee
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        $employeeData = $employee->toArray();
        $employeeData['avatar'] = asset('/uploads/employee_image/'.$employee->avatar); 
        return response()->json([
            'status'=>true,
            'data'=>$employeeData
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //$employee = Employee::find();

        dd($request->all());
        /*
        
        if ($request->isMethod('post')){
            //For File Upload
            $emp_avatar='';
            if($request->hasFile($request->input('avatar'))){
                $dir = public_path('/uploads/employee_image');
                $emp_avatar=time().'.'.$request->file('avatar')->getClientOriginalExtension();
                $NewFilePath=$request->file('avatar')->move($dir, $emp_avatar);
                if(!file_exists(public_path('/uploads/employee_image/'.$emp_avatar))) {
                    return response()->json([
                        'status'=>false,
                        'message'=>'Problame in file upload',
                        'data'=>''
                    ]);
                }            
            }
            $employeeData = new Employee;
            
            $employeeData1= $request->all();
            $employeeData1['avatar'] = $emp_avatar; 
            unset($employeeData1['token']);
            $employeeData->create($employeeData1);
            return response()->json([
                'status'=>true,
                'message'=>'Employee created successfully',
                'data'=>$employeeData1
            ]);
        }
        */
        $employeeData = $employee->toArray();
        $employeeData['avatar'] = asset('/uploads/employee_image/'.$employee->avatar); 

        if($request->name != "") $employee->name = $request->name;
        if($request->dob != "") $employee->dob = $request->dob;
        
        if(!$request->avatar->isEmpty()) {
            dd($request->avatar);

            if (\File::exists(public_path('/uploads/employee_image/'.$employee->avatar))) { // unlink or remove previous image from folder
                unlink(public_path('/uploads/employee_image/'.$employee->avatar));
            }
            //For File Upload
            $emp_avatar='';
            //$UserUploadedImageURL='';
            if($request->hasFile($request->input('avatar'))){
                $dir = public_path('/uploads/employee_image');
                $emp_avatar=time().'.'.$request->file('avatar')->getClientOriginalExtension();
                $NewFilePath=$request->file('avatar')->move($dir, $emp_avatar);
                if(!file_exists(public_path('/uploads/employee_image/'.$emp_avatar))) {
                    return response()->json([
                        'status'=>false,
                        'message'=>'Problame in file upload',
                        'data'=>''
                    ]);
                }
                $employee->dob = $this->emp_avatar;
            }

        }
        dd($employee);
/* 
        $this->validate($request,[
            'name' => 'required',
            'dob' => 'required'
        ]);
        try {  
            $employeeData = $employee->toArray();
            $employeeData['avatar'] = asset('/uploads/employee_image/'.$employee->avatar); 

            if($request->name != "") $employee->name = $request->name;
            if($request->dob != "") $employee->dob = $request->dob;
            
            if(isEmpty($request->avatar)) {
                
                if (\File::exists(public_path('/uploads/employee_image/'.$employee->avatar))) { // unlink or remove previous image from folder
                    unlink(public_path('/uploads/employee_image/'.$employee->avatar));
                }
                //For File Upload
                $emp_avatar='';
                //$UserUploadedImageURL='';
                if($request->hasFile($request->input('avatar'))){
                    $dir = public_path('/uploads/employee_image');
                    $emp_avatar=time().'.'.$request->file('avatar')->getClientOriginalExtension();
                    $NewFilePath=$request->file('avatar')->move($dir, $emp_avatar);
                    if(!file_exists(public_path('/uploads/employee_image/'.$emp_avatar))) {
                        return response()->json([
                            'status'=>false,
                            'message'=>'Problame in file upload',
                            'data'=>''
                        ]);
                    }
                    $employee->dob = $this->emp_avatar;
                    dd($employee->dob);         
                }

            }

            //$employeeData = new Employee;
            
            //if($employee->save()){
                return response()->json([
                    'status' => 'ok',
                    //'data' => "Successfully updated."
                    'data' => "file deleted ".$employee
                ], 201);
            //}
        } catch(QueryException $ex){
            $msg = "Employee Controller : Database error.";
            if(env('APP_DEBUG')) $msg = $ex->getMessage();
            return response()->json([
                'status' => 'error',
                'data' => $msg
            ], 400);
       }
*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
