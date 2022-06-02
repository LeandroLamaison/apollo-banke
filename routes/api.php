<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', function (Request $request) {

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
 });
 
 
