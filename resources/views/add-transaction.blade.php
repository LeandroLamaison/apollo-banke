@extends('adminlte::page')

@section('title', 'Apollo Banke')

@section('content_header')
    <h1>{{ __('transaction.add_' . $data['type']) }}</h1>
@stop

@section('content')
    {{ $data['type'] }}
@stop

@section('css')
    <style></style>
@stop

@section('js')
    <script></script>
@stop