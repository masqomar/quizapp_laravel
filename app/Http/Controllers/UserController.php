<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mail;

class UserController extends Controller
{
    public function register(Request $request){

        try {
            
            $plainPassword=$request->password;
            $password=bcrypt($request->password);
            $request->request->add(['password' => $password]);
            // create the user account 
            $created=User::create($request->all());
            $request->request->add(['password' => $plainPassword]);
            // login now..
            return $this->login($request);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 401);
        }
        
    }

    public function login(Request $request)
    {
        
        // header('Content-Type: application/json');
        // header("Access-Control-Allow-Origin: *");		// CORS
        // header("Access-Control-Allow-Headers: Access-Control-Allow-Origin, Accept");    

        $input = $request->only('email', 'password');
        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }

    
        // get the user 
        $user = Auth::user();
       
        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully',
            'token' => $jwt_token,
            'data' => $user
        ]);
    }

    public function logout(Request $request)
    {       
        //get token manually
        //$token = $request->header( 'Authorization' );

        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function getCurrentUser(Request $request){
        
       try {
        $user = JWTAuth::parseToken()->authenticate();   
        
        return response()->json([
                'success' => true,
                'message' => "User found!",
                'data' => $user
            ]);
        
        return $user;
       } catch (\Throwable $th) {
           return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
       }
       
    }

   
    public function update(Request $request){

        try {
            $data = JWTAuth::parseToken()->authenticate();

            if(!$data){
                return response()->json([
                    'success' => false,
                    'message' => 'User is not found'
                ]);
            }

            $updatedUser = User::where('id', $data->id)->update($request->all());
            $user =  User::find($data->id);

            return response()->json([
                'success' => true, 
                'message' => 'Information has been updated successfully!',
                'data' => $user
            ]);
        } catch (JWTException $exception) {
             return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }
}
