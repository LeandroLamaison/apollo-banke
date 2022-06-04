<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

    public function store() {

    }
}
