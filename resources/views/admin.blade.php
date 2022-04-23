@extends('adminlte::page')

@section('title', 'Apollo Banke')

@section('content_header')
    <h1>{{__('main.admin_page')}}</h1>
@stop

@section('content')
    <x-balance-card 
        :title="__('main.total_balance')"
        :balance="$data['globalBalance']" 
    />

    Histórico Geral
@stop
