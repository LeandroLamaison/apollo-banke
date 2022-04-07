<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Client;
use App\Rules\CPF;
use App\Rules\UF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

function generateCardNumber (string $cpf) {
    $cardPrefix = config('constants.apollo.card_prefix');
    $part1 = substr($cpf, 0, 3);
    $part2 = substr($cpf, 3, 3);
    $part3 = substr($cpf, 6, 3);
    return $cardPrefix . $part1 + 1000 . $part2 + 2000 . $part3 + 3000;
}

function generateSecurityCode () {
    return rand(0, 9) . rand(0, 9) . rand(0, 9);
}

function getDueDate () {
    $timestamp = strtotime('+2 years');
    return date('d-m-y', $timestamp);
}

class ClientController extends Controller
{
    public function view () {
        $userId = Auth::id();
        $client = Client::where('user_id', $userId)->first();
        $account = Account::where('user_id', $userId)->first();

        $data = [
            'client' => $client,
            'account' =>$account,
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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', new CPF],
            'cellphone' => ['required', 'string', 'min:13', 'max:13'],
            'birth_date' => ['required', 'string'],
            'city' => ['required', 'string' , 'max:255'],
            'uf' => ['required', 'string', new UF]
        ]);

        $form = $request->all();
        $userId = Auth::id();

        $cardNumber = generateCardNumber($form['cpf'], $userId);
        $securityCode = generateSecurityCode();
        $dueDate = getDueDate();

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
                'card_number' => $cardNumber,
                'security_code' => $securityCode,
                'due_date' => $dueDate,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return redirect('dashboard');
    }
}
