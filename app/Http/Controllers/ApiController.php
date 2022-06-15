<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ExternalTransfer;
use App\Models\User;
use App\Services\AccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $recipientAccount = Account::where(['card_number' => $form['recipientCardNumber']])
            ->select('card_number', 'balance')
            ->first();
            
        if (!$recipientAccount) {
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

        $newRecipientBalance = AccountService::balance(
            $recipientAccount['balance'], 
            $form['value'], 
            'deposit'
        );

        $newTransfer = [
            'sender_bank_id' => $senderBankId,
            'recipient_bank_id' => $recipientBankId,
            'sender_card_number' => $form['senderCardNumber'],
            'recipient_card_number' => $form['recipientCardNumber'],
            'value' => $form['value']
        ];
        
        DB::beginTransaction();
        try {
            $responseData = ExternalTransfer::create($newTransfer);
            
            Account::where(['card_number' => $newTransfer['recipient_card_number']])
                ->update(['balance' => $newRecipientBalance]);

            DB::commit();

            $response = [
                'success' => true,
                'data'    => $responseData,
                'message' => 'Transference ocurred successfully',
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            DB::rollback();

            $response = [
                'success' => false,
                'data'    => 'Error',
                'message' => 'Internal Server Error' ,
            ];
            return response()->json($response, 500);
        }
    }

    public function get(Request $request) {
        $request->validate([
            'id' => ['required', 'number'],
        ]);
        $form = $request->all();

        $senderBankId = Auth::id();

        $filter = [
            'sender_bank_id' => $senderBankId,
            'id' => $form['id'],
        ];

        $transfer = ExternalTransfer::where($filter)->first();

        $response = [
            'success' => true,
            'data'    => $transfer,
        ];
        return response()->json($response, 200);
    }
}
