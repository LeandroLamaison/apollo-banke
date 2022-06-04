<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ExternalTransfer;
use App\Models\User;
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
        $request->validate([
            'senderCardNumber' => ['required', 'string'],
            'recipientCardNumber' => ['required', 'string'],
            'value' => ['required', 'numeric']
        ]);
        $form = $request->all();

        $account = Account::where(['card_number' => $form['recipientCardNumber']])->first();
        if (!$account) {
            $response = [
                'success' => false,
                'data'    => ['recipientCardNumber' => $form['recipientCardNumber']],
                'message' => 'Account does not exist' ,
            ];
            return response()->json($response, 403);
        }

        $recipientBank= User::where(['email' => env('BANK_MAIL')])->select('id')->first();

        $senderBankId = Auth::id();
        $recipientBankId = $recipientBank['id'];

        $newExternalTransfer = [
            'sender_bank_id' => $senderBankId,
            'recipient_bank_id' => $recipientBankId,
            'sender_card_number' => $form['senderCardNumber'],
            'recipient_card_number' => $form['recipientCardNumber'],
            'value' => $form['value']
        ];
        $responseData = ExternalTransfer::create($newExternalTransfer);

        $response = [
            'success' => true,
            'data'    => $responseData,
            'message' => 'Transference ocurred successfully',
        ];
        return response()->json($response, 200);
    }

    public function load(Request $request) {
        $request->validate([
            'senderCardNumber' => ['required', 'string'],
        ]);
        $form = $request->all();

        $senderBankId = Auth::id();

        $filter = [
            'sender_bank_id' => $senderBankId,
            'sender_card_number' => $form['senderCardNumber'],
        ];

        $transfers = ExternalTransfer::where($filter)->orderBy('created_at', 'desc')->get();
        $response = [
            'success' => true,
            'data'    => $transfers,
        ];
        return response()->json($response, 200);
    }
}
