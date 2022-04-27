
@props(['dados', 'admin'])

<div class="card-body p-0">
    <table class="table table-striped">
        <thead>
            <tr>
                <th style="width: 10px">#</th>
                <th>Data</th>
                @if ($admin)
                    <th>Id da Conta</th>
                @endif
                <th>Tipo</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($dados as $dadoss => $v){ ?>
                <tr>
                    <th></th>
                    <th><?php echo date_format(date_create($v['created_at']),"d/m/Y"); ?></th>
                    @if ($admin)
                    <th><?php echo $v['account_id'] ?></th>
                    @endif
                    <th><?php echo $v['type'] == 'deposit' ? 'Depósito' : 'Saque'; ?></th>
                    <th class="{{ $v['type'] == 'deposit' ? 'text-green' : 'text-red'}}">
                        <?php echo 'R$ ' . number_format($v['value'], 2, ',', ''); ?>
                    </th>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div