<?php

namespace App\Http\Controllers;


class TransactionController extends Controller
{
    public function create (string $type) {
        if($type !== 'deposit' && $type !== 'withdrawal') {
            return redirect('dashboard');
        }

        return view('add-transaction', ['data' => ['type' => $type]]);
    }

    public function store (string $type) {
        if($type !== 'deposit' && $type !== 'withdrawal') {
            return redirect('dashboard');
        }

        return view('dashboard');
    }

}
