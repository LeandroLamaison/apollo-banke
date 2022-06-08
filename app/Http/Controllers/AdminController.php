<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ExternalTransfer;
use App\Models\Transaction;
use App\Models\User;
use App\Services\HistoryService;

class AdminController extends Controller
{
    public function view () {
        $bank = User::where(['is_bank' => true, 'email' => env('BANK_MAIL')])
            ->select('id')
            ->first();

        $globalBalance = Account::sum('balance');
        
        $transactions = Transaction::get();
        $externalTransfers = ExternalTransfer::get();

        $history = HistoryService::buildHistory($transactions, null, $externalTransfers,  null, $bank['id']);
        
        $data = [
            'globalBalance' => $globalBalance,
            'history' => $history
        ];

        config(['adminlte.layout_topnav' => true]);
        return view('admin', ['data' => $data]);
    }
}
