<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Rules\CPF;
use App\Rules\UF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
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

        Client::create([
            'name' => $form['name'],
            'cpf' => $form['cpf'],
            'cellphone' => $form['cellphone'],
            'birth_date' => $form['birth_date'],
            'city' => $form['city'],
            'uf' => $form['uf'],
            'user_id' => $userId,
        ]);

        return redirect('dashboard');
    }
}
