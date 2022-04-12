@extends('adminlte::page')

@section('title', 'Apollo Banke')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <h4>Bem vindo, {{$data['user']['name']}}</h4>

    <x-adminlte-card 
        class="mt-5 text-xl text-center"
        title="Saldo Disponível" 
        theme="lightblue" 
        theme-mode="outline"
        icon="fas fa-lg fa-solid fa-money-bill" 
        header-class="text-uppercase rounded-bottom border-info"
    >
        {{ $data['account']['balance'] }}
    </x-adminlte-card>

    <x-adminlte-card 
        class="mt-5 grid"
        title="Conta" 
        theme="lightblue" 
        theme-mode="outline"
        icon="fas fa-lg fa-solid fa-user" 
        header-class="text-uppercase rounded-bottom border-info"
    >   
        <div class="mt-0 col-center text-center">
            <span class="block">
                Número do Cartão
            </span>
            <span class="text-xl">
                {{ $data['account']['card_number'] }}
            </span>
        </div>
        <div class="mt-4 col-start text-center">
            <b class="block">Membro desde</b> {{$data['account']['creation_date']}}
        </div>
        <div class="mt-4 col-center text-center">
            <b class="block">Validade</b> {{$data['account']['due_date']}}
        </div>
        <div class="mt-4 col-end text-center">
            <b class="block">CVV</b> {{$data['account']['security_code']}}
        </div>
    </x-adminlte-card>

@stop

@section('css')
    <style>

        .grid {
            display: grid;
            grid-template-columns: 20% 60% 20%;
        }

        .col-start {
            grid-column: 1;
        }
        .col-center {
            grid-column: 2;
        }   
        .col-end {
            grid-column: 3;
        }

        .flex {
            display: flex;
            justify-content: space-between;
            padding: 0px 28px;
        }

        .block {
            display: block;
        }
    </style>
    <!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
@stop

@section('js')
    <script></script>
@stop