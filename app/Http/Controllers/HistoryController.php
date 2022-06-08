<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ExternalTransfer;
use App\Models\Transaction;
use App\Models\InternalTransfer;
use App\Models\User;
use App\Services\HistoryService;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function view () {
        $user = Auth::user();

        $account = Account::where('user_id', $user->id)
            ->select('id', 'card_number')
            ->first();

        $bank = User::where(['is_bank' => true, 'email' => env('BANK_MAIL')])
            ->select('id')
            ->first();

        $transactions = Transaction::where('account_id', $account['id'])
            ->select('type','value','created_at')
            ->get();

        $internalTransfers = InternalTransfer::where('sender_account_id', $account['id'])
            ->orWhere('recipient_account_id', $account['id'])
            ->get();
        
        $externalTransfers = ExternalTransfer::where([
            'sender_bank_id' => $bank['id'], 
            'sender_card_number' => $account['card_number']
        ])->orWhere([
            'recipient_bank_id' => $bank['id'],
            'recipient_card_number' => $account['card_number']
        ])->get();

        
        
        $history = HistoryService::buildHistory($transactions, $internalTransfers, $externalTransfers, $account, $bank['id']);
        
        $data = [ 
            'history' => $history
        ];

        return view('history', ['data' => $data]);
    }

}
