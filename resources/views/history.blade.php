@extends('adminlte::page')

@section('title', 'Apollo Banke')

@section('content_header')
    <h1>{{ __('transaction.history_transactions') }}</h1>
@stop

@section('content')

<div class="card">
   <x-history-table :history="$data['history']" :admin="false"></x-history-table>
</div>

@stop

@section('css')
    <style></style>
@stop

@section('js')
    <script></script>
@stop