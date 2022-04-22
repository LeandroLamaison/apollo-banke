<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Client;
use App\Rules\CPF;
use App\Rules\UF;
use App\Services\AccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    public function view () {
        $user = Auth::user();
        $account = Account::where('user_id', $user->id)->first();

        $data = [
            'user' => $user,
            'account' => AccountService::format($account),
        ];
        
        return view('dashboard', ['data' => $data]);
    }

    public function create () {
        $userId = Auth::id();
        $clients = Client::where('user_id', $userId)->count();

        if($clients > 0) {
            return redirect('dashboard');
        }

        return view('add-client-info');
    }

    public function store (Request $request) {
        Log::debug('Starting function');
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', new CPF],
            'cellphone' => ['required', 'string', 'min:13', 'max:13'],
            'birth_date' => ['required', 'string'],
            'city' => ['required', 'string' , 'max:255'],
            'uf' => ['required', 'string', new UF]
        ]);
        Log::debug('Validated');

        $form = $request->all();
        Log::debug('Got form');

        $userId = Auth::id();
        Log::debug('Got user id');


        $newAccount = AccountService::create($form['cpf']);
        Log::debug('Account service executed');

        DB::beginTransaction();
        try {
            Client::create([
                'user_id' => $userId,
                'name' => $form['name'],
                'cpf' => $form['cpf'],
                'cellphone' => $form['cellphone'],
                'birth_date' => $form['birth_date'],
                'city' => $form['city'],
                'uf' => $form['uf'],
            ]);
    
            Account::create([
                'user_id' => $userId,
                'card_number' => $newAccount['card_number'],
                'security_code' => $newAccount['security_code'],
                'due_date' => $newAccount['due_date'],
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::debug('Transaction throwed');
            throw $e;
        }

        return redirect('dashboard');
    }
}
