<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ExternalTransfer;
use App\Models\User;
use App\Services\AccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            dd($form);
            return abort(404, Lang::get('validation.valid_bank'));
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
            ExternalTransferController::call($newTransfer);
        } catch (\Exception $e) {
            return abort(404, Lang::get('validation.transfer'));
        }

        DB::beginTransaction();
        try {
            ExternalTransfer::create($newTransfer);
            
            Account::where(['card_number' => $newTransfer['sender_card_number']])
                ->update(['balance' => $newSenderBalance]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return abort(404, Lang::get('validation.transfer'));
        }

        return redirect('dashboard');
    }

    private function call($newTransfer) {

    }
}