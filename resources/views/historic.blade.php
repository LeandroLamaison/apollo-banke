@extends('adminlte::page')

@section('title', 'Apollo Banke')

@section('content_header')
    <h1>{{ __('transaction.historic') }}</h1>
@stop

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Histórico de Movimentações</h3>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

@stop

@section('css')
    <style></style>
@stop

@section('js')
    <script></script>
@stop