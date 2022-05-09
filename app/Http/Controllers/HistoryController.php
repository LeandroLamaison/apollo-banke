<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\InternalTransfer;
use App\Services\HistoryService;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function view () {
        $user = Auth::user();

        $account = Account::where('user_id', $user->id)->select('id')->first();

        $transfers = InternalTransfer::where('sender_account_id', $account->id)->orWhere('recipient_account_id', $account->id)->get();
        $transactions = Transaction::where('account_id', $account->id)->select('type','value','created_at')->get();
        
        $history = HistoryService::buildHistory($transactions, $transfers, $account['id'],);
        
        $data = [ 
            'history' => $history
        ];

        return view('history', ['data' => $data]);
    }

}
