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
        
        $dados_transfer = HistoryService::transferToTransaction($account['id'], $transfers);
        $dados_transaction = Transaction::where('account_id', $account->id)->select('type','value','created_at')->get();

        $history = array_merge($dados_transfer, json_decode(json_encode($dados_transaction), true));

        $dados = [ 
            'history' => usort($history, function($a, $b) {
                return !strcmp($a['created_at'], $b['created_at']);
            })
        ];

        // echo json_encode($history);

        return view('history', ['dados' => $history]);
    }

}
