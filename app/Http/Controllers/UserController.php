<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\User;
use App\Role;
use App\CompanyLocation;
use App\Permission;
use Auth;
use JWTAuthException;
class UserController extends Controller
{   

    private $user;
    public function __construct(User $user){
        $this->user = $user;
    }

    public function getAllUser()
    {
        return User::all();
    }
/*
    private function getRolesAbilities($user)
    {
        $userRole = $user->Roles->first();
        $abilities = [];
        $roles = Role::where('id','=',$userRole->id)->get();
        foreach ($roles as $role) {
            if (!empty($role->name)) {
                $abilities[$role->name] = [];
                $rolePermission = $role->permissions()->get();
                foreach ($rolePermission as $permission) {
                    if (!empty($permission->name)) {
                        array_push($abilities[$role->name], $permission->name);
                    }
                }
            }
        }
        return $abilities;
    }
*/   
    public function register(Request $request){
        $user = $this->user->create([
          'name' => $request->get('name'),
          'email' => $request->get('email'),
          'password' => bcrypt($request->get('password'))
        ]);
        return response()->json(['status'=>true,'message'=>'User created successfully','data'=>$user]);
    }
    
    public function login(Request $request){
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['status'=>'error','msg'=>'Invalid email or password'], 422);
                // return response()->json([
                //     'status' => 'error',
                //     'msg' => "Invalid email or password."
                // ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json(['failed_to_create_token'], 500);
        }
///////
        $user = Auth::user();
        $userId = $user->id;
        $abilities=$user->allPermissions();
        //$abilities = $this->getRolesAbilities($user);
        $userRole = [];

        foreach ($user->Roles as $role) {
           $userRole [] = $role->name;
        }
///////
        $status = "ok";
        $msg = "Successfully logged in.";

        $comany_location = CompanyLocation::first();

        $updateFCMToken = User::where('id', $userId)->update(['FCM_device_id' => $request->FCMDeviceId]);
        
        return response()->json(compact('status', 'msg', 'userId', 'token', 'userRole', 'abilities', 'comany_location'));
        // return response()->json([
        //     'status' => 'ok',
        //     'msg' => "Successfully logged in.",
        //     'token' => $token
        // ]);
    }

    public function getAuthUser(Request $request){
        $user = JWTAuth::toUser($request->token);
        return response()->json(['result' => $user]);
    }
}  