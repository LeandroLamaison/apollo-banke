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
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($dados as $dadoss => $v){ ?>
                    <tr>
                        <th></th>
                        <th><?php echo date_format(date_create($v['created_at']),"d/m/Y"); ?></th>
                        <th><?php echo $v['type'] == 'deposit' ? 'Depósito' : 'Saque'; ?></th>
                        <th><?php echo 'R$ ' . number_format($v['value'], 2, ',', ''); ?></th>
                    </tr>
                <?php } ?>
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