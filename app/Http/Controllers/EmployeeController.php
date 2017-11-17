<?php

namespace App\Http\Controllers;
use JWTAuth;
use App\Employee;
use App\Department;
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
        try{
            $employees = Employee::with([
                'department'=> function($query){
                    $query->select('id', 'name');
                },
            ])->get();
            foreach($employees as $employeeData){
                $employeeData['avatar'] = asset('/uploads/employee_image/'.$employeeData['avatar']);
                $employeeData['department_name'] = $employeeData['department']['name'];
                unset($employeeData['department']);
            }
            return response()->json([
                'status'=>"ok",
                'data'=>$employees
            ]);
        }catch(\Exception $ex){
            return response()->json([
                'status'=>"error",
                'data'=>""
            ]);
        }
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
            $user_id = JWTAuth::toUser($request->token)->id;
            $request->request->add(['user_id' => $user_id]);
            $employeeData1 = $request->all();
            $employeeData1['avatar'] = $fileName;
            unset($employeeData1['token']);
            $employeeData->create($employeeData1);
            return response()->json([
                'status'=>"ok",
                'message'=>'Employee created successfully',
                'data'=>$employeeData1
            ]);
        }catch(\Exception $ex){
            return response()->json([
                'status'=>"error",
                'message'=>'Something Went Wrong.',
                'data'=>""
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
        try{
            return response()->json([
                'status'=>"ok",
                'data'=>$employee
            ]);
        }catch(\Exception $ex){
            return response()->json([
                'status'=>"error",
                'data'=>"Something Went Wrong."
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        try{
            $employeeData = $employee->toArray();
            $employeeData['avatar'] = asset('/uploads/employee_image/'.$employee->avatar); 
            return response()->json([
                'status'=>"ok",
                'data'=>$employeeData
            ]);
        }catch(\Exception $ex){
            return response()->json([
                'status'=>"error",
                'data'=>"Something Went Wrong."
            ]);
        }
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
        try{
            $employeeData = $request->all();
            //dd($employeeData);
            if(!empty($request->avatar)){
                /// Decode your image/file
                $data = base64_decode($request->avatar);
                $f = finfo_open();
                $mime_type = finfo_buffer($f, $data);
                $ext = explode(' ', $mime_type);
                /// file name
                $fileName= str_replace(".","",microtime(true)).'.'.strtolower($ext[0]);
                /// Upload path
                $dir = public_path('/uploads/employee_image/');
                $uploadPath = $dir.$fileName;
    
                /// Upload the decoded file/image
                if(!file_put_contents($uploadPath , $data)){
                    return response()->json([
                        'status'=>false,
                        'message'=>'Unable to save file',
                    ]);
                }
                $employeeData['avatar'] = $fileName;
                if(file_exists(public_path('/uploads/employee_image/'.$employee->avatar))) {
                    unlink(public_path('/uploads/employee_image/'.$employee->avatar));
                }
            }
            $employee->update($employeeData);
            return response()->json([
                'status'=>"ok",
                'message'=>'Employee Update successfully',
            ]);
        }catch(\Exception $ex){
            return response()->json([
                'status'=>"error",
                'message'=>'Something went Wrong.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee){
        try {
			$employee->delete();
			return response()->json([
				'status' => "ok",
				'data' => "Successfully deleted."
			], 200);
		} catch(\Exception $ex){
			return response()->json([
				'status' => "error",
				'data' => "Something Went Wrong."
			], 400);
		}
    }
}
