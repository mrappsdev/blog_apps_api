<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class LoginController extends Controller
{
    /**
     * User login API method
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user             = Auth::user();
            $success['name']  = $user->name;
            $success['token'] = $user->createToken('accessToken')->accessToken;

            return sendResponse($success, 'You are successfully logged in.');
        } else {
            return sendError('Unauthorised', ['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * User registration API method
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $success['name']  = $user->name;
            $message          = 'Yay! A user has been successfully created.';
            $success['token'] = $user->createToken('accessToken')->accessToken;
        } catch (Exception $e) {
            $success['token'] = [];
            $message          = 'Oops! Unable to create a new user.';
        }

        return sendResponse($success, $message);
    }

    public function deleteUser($id,Request $request){
        $header = $request->header('Authorization');
       if(empty($header)){
        return response()->json(['message'=>'Authorization is missing'],422);
       }else{
           if($header == 'BearereyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6Ik5hem11bCBIb3F1ZSIsImlhdCI6MTUxNjIzOTAyMn0.eXQUr8Mq2ulWt-aVRlxsezDju-URRLLvmcs6XamhGO4'){
            
            User::where('id',$id)->delete();
            return response()->json(['message'=>'User Successfully Deleted'],200);
            //$users = User::get();
            //return response()->json(['users'=>$users],200);
           }else{
            return response()->json(['message'=>'Authorization is incorrect'],422); 
           }
       }

    }

      //secure get api for fetch users 
   public function usersList(Request $request){
           if(2>1){
            $users = User::get();
            return response()->json(['users'=>$users],200);
           }else{
            return response()->json(['message'=>'Authorization is incorrect'],422); 
           }
   }
}