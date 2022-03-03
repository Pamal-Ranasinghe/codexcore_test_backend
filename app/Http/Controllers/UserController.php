<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * User registration function
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function register(Request $request){
        
        try {
            $fields = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password'=> 'required|string|confirmed'
            ]);

            $user = User::create([
                'name' => $fields['name'],
                'email' => $fields['email'],
                'password' => bcrypt($fields['password']),

            ]);

            $token = $user->createToken('token')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token
            ];

            return response($response, 201);

        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }

    /**
     * User log out function
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response $response
     */
    public function logout(Request $request){
        try {
            auth()->user()->tokens()->delete();
            $response = [
                'message' => 'logged out',
            ];
            return response($response, 200);
        } catch (Throwable $e) {
            report($e);
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }
    /**
     * User login function
     * 
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */
    public function login(Request $request){

        try {
            $fields = $request->validate([
                'email' => 'required|string',
                'password' => 'required|string'
            ]);
    
            $user = User::where('email', $fields['email'])->first();

                
            if(!$user || !Hash::check($fields['password'], $user->password)){
                return response ([
                    'message' => 'Bad Credentials'
                ], 401);
            }

            $token = $user->createToken('token')->plainTextToken;  
            
            return response()->json(['token' => $token, 'public_id' => $user->public_id, 'message' => 'Login Successfull'], 200);

        } catch (Throwable $e) {
            return response()->json(['message' => 'Something went wrong', 'error' => $e], 500);
        }
        
    }
}
