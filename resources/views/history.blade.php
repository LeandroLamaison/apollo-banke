@extends('adminlte::page')

@section('title', 'Apollo Banke')

@section('content_header')
    <h1>{{ __('transaction.history') }}</h1>
@stop

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Histórico de Movimentações</h3>
    </div>

   <x-history-table :dados="$dados" :admin="false"></x-history-table>
</div>

@stop

@section('css')
    <style></style>
@stop

@section('js')
    <script></script>
@stop