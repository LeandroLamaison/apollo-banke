<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternalTransferController extends Controller
{
    public function create () {
        $user = Auth::user();

        $account = Account::where(['user_id' => $user['id']])
            ->first();

        $clients = Client::where('user_id', '!=', $user['id'])
            ->select('user_id', 'name')
            ->orderBy('name', 'asc')
            ->get();
        
        $data = [
            'account' => $account,
            'clients' => $clients
        ];

        return view('add-transfer', ['data' => $data]);
    }

    public function store (Request $request) {
        return redirect('dashboard');
    }

}
