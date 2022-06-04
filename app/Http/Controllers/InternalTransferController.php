<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Client;
use App\Models\InternalTransfer;
use App\Models\User;
use App\Rules\ValidUser;
use App\Services\AccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

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
        $request->validate([
            'user' => ['required', 'numeric', new ValidUser],
            'value' => ['required', 'numeric']
        ]);
        $form = $request->all();

        $value = $form['value'];

        $sender = Auth::user();
        $senderAccount = Account::where(['user_id' => $sender['id']])
            ->select('id', 'balance')
            ->first();

        $receiver = User::where(['id' => $form['user']])
            ->select('id')
            ->first();
        $receiverAccount = Account::where(['user_id' => $receiver['id']])
            ->select('id', 'balance')
            ->first();

        $newTransfer = [
          'sender_account_id' => $senderAccount['id'],
          'recipient_account_id' => $receiverAccount['id'],
          'value' => $value
        ];

        $newSenderBalance = AccountService::balance(
            $senderAccount['balance'], 
            $value, 
            'withdrawal'
        );

        $newReceiverBalance = AccountService::balance(
            $receiverAccount['balance'], 
            $value, 
            'deposit'
        );

        DB::beginTransaction();
        try {
            InternalTransfer::create($newTransfer);
            
            Account::where(['id' => $newTransfer['sender_account_id']])
                ->update(['balance' => $newSenderBalance]);

            Account::where(['id' => $newTransfer['recipient_account_id']])
                ->update(['balance' => $newReceiverBalance]);
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return abort(404, Lang::get('validation.transfer'));
        }
        
        return redirect('dashboard');
    }

}
