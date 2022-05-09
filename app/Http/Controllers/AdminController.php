<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Services\HistoryService;

class AdminController extends Controller
{
    public function view () {
        $globalBalance = Account::sum('balance');
        $transactions = Transaction::orderBy('created_at', 'DESC')->get();

        $history = HistoryService::buildHistory($transactions, null, null);
        
        $data = [
            'globalBalance' => $globalBalance,
            'history' => $history
        ];

        config(['adminlte.layout_topnav' => true]);
        return view('admin', ['data' => $data]);
    }
}
