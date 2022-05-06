<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;

class AdminController extends Controller
{
    public function view () {
        $globalBalance = Account::sum('balance');
        $transactions = Transaction::select('type','value', 'account_id', 'created_at')->orderBy('created_at', 'DESC')->get();

        $data = [
            'globalBalance' => $globalBalance,
            'transactions' => $transactions
        ];

        config(['adminlte.layout_topnav' => true]);
        return view('admin', ['data' => $data]);
    }
}
