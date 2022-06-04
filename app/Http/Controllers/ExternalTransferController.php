<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ExternalTransfer;
use App\Models\User;
use App\Services\AccountService;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http as FacadesHttp;
use Illuminate\Support\Facades\Lang;

class ExternalTransferController extends Controller
{
    public function create () {
        $user = Auth::user();

        $account = Account::where(['user_id' => $user['id']])
            ->first();

        $banks = User::where(['is_bank' => true])
            ->where('email', '!=', env('BANK_MAIL'))
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();
            
        $data = [
            'account' => $account,
            'banks' => $banks,
        ];

        return view('add-external-transfer', ['data' => $data]);
    }

    public function store(Request $request) {
        $request->validate([
            'bank' => ['required', 'numeric'],
            'account' => ['required', 'numeric'],
            'value' => ['required', 'numeric']
        ]);
        $form = $request->all();

        $recipientBank = User::where(['is_bank' => true, 'id' => $form['bank']])
        ->select('id', 'email')
        ->first();

        if(!$recipientBank) {
            throw new ErrorException(Lang::get('validation.valid_bank'));
        }

        $senderBank = User::where(['is_bank' => true, 'email' => env('BANK_MAIL')])
            ->select('id')
            ->first();
        
        $userId = Auth::id();
        $senderAccount = Account::where(['user_id' => $userId])
            ->select('card_number', 'balance')
            ->first();

        $newSenderBalance = AccountService::balance(
            $senderAccount['balance'], 
            $form['value'], 
            'withdrawal'
        );

        $newTransfer = [
            'sender_bank_id' => $senderBank['id'],
            'recipient_bank_id' => $recipientBank['id'],
            'sender_card_number' => $senderAccount['card_number'],
            'recipient_card_number' => $form['account'],
            'value' => $form['value']
        ];

        try {
            ExternalTransferController::callApi($recipientBank['email'], $newTransfer);
        } catch (\Exception $e) {
            throw $e;
        }

        DB::beginTransaction();
        try {
            ExternalTransfer::create($newTransfer);
            
            Account::where(['card_number' => $newTransfer['sender_card_number']])
                ->update(['balance' => $newSenderBalance]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return redirect('dashboard');
    }


    private function callApi($bank, $newTransfer) {
        if ($bank !== env('TWIN_BANK_MAIL')) {
            throw new ErrorException(Lang::get('validation.transfer'));
        }

        return ExternalTransferController::callTwinBank($newTransfer);
    }

    private function callTwinBank($newTransfer) {
        $url = env('TWIN_BANK_API_URL');

        $loginData = FacadesHttp::post($url.'/login', [
            'email' => env('TWIN_BANK_API_MAIL'),
            'password' => env('TWIN_BANK_API_PASSWORD')
        ])->json();


        if (!is_array($loginData['data']) || !$loginData['data']['token']) {
            throw new ErrorException(Lang::get('validation.transfer'));
        }

        $response = FacadesHttp::withHeaders([
            'Content-Type' => 'application/json',
            'authorization' => 'Bearer ' . $loginData['data']['token']
        ])->post($url.'/transfer', [
            "senderCardNumber" => $newTransfer['sender_card_number'],
            "recipientCardNumber" => $newTransfer['recipient_card_number'],
            "value" => $newTransfer['value']
        ])->json();

        if(!is_array($response) || !$response['success']) {
            throw new ErrorException(Lang::get('validation.transfer'));
        }
    }
}
