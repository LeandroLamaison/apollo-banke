<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function login (Request $request) {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $authUser = Auth::user();
            $success['token'] =  $authUser->createToken($authUser)->plainTextToken;
            $success['username'] =  $authUser->name;
      
            $response = [
                'success' => true,
                'data'    => $success,
                'message' => 'User logged successfully',
            ];
    
            return response()->json($response, 200);
        }
    
        $response = [
            'success' => false,
            'data'    => 'Error',
            'message' => 'User not authenticated',
        ];
        
        return response()->json($response, 401);
    }

    public function post(Request $request) {
        return response()->json('Post', 200);
    }

    public function load(Request $request) {
        return response()->json('Load', 200);
    }
}
