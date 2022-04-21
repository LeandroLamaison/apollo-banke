<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Rules\TransactionType;
use App\Services\AccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TransactionController extends Controller
{
    public function create (string $type) {
        if($type !== 'deposit' && $type !== 'withdrawal') {
            return redirect('dashboard');
        }

        $user = Auth::user();
        $account = Account::where('user_id', $user->id)->first();

        $data = [
            'type' => $type,
            'account' => $account,
        ];

        return view('add-transaction', ['data' => $data]);
    }

    public function store (Request $request) {
        $request->validate([
            'type' => ['required', 'string', new TransactionType],
            'value' => ['required', 'numeric']
        ]);
        $form = $request->all();

        $user = Auth::user();
        $account = Account::where('user_id', $user->id)->first();

        $newTransaction = [
            'type' => $form['type'],
            'value' => $form['value'],
            'account_id' => $account['id'],
        ];

        $newBalance = AccountService::balance(
            $account['balance'], 
            $newTransaction['value'], 
            $newTransaction['type']
        );

        DB::beginTransaction();
        try {
            Transaction::create($newTransaction);
            
            Account::where(['id' => $newTransaction['account_id']])
                ->update(['balance' => $newBalance]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }


        return redirect('dashboard');
    }

}
