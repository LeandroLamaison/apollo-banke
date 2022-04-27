<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\InternalTransfer;
use App\Rules\TransactionType;
use App\Services\AccountService;
use App\Services\HistoryService;
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

        return view('historic', ['dados' => $history]);
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
