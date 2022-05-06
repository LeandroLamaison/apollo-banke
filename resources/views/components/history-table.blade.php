
@props(['history', 'admin'])

<div class="card-body p-0">
    <table class="table table-striped">
        <thead>
            <tr>
                <th style="width: 10px">#</th>
                <th>{{ __('main.date') }}</th>
                @if ($admin)
                    <th>{{ __('account.account_id') }}</th>
                @endif
                <th>{{ __('main.type') }}</th>
                <th>{{ __('main.value') }}</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($history as $transactions => $transaction){ ?>
                <tr>
                    <th></th>
                    <th><?php echo date_format(date_create($transaction['created_at']),"d/m/Y"); ?></th>
                    @if ($admin)
                    <th><?php echo $transaction['account_id'] ?></th>
                    @endif
                    <th><?php echo $transaction['type'] == 'deposit' ? __('transaction.deposit') : __('transaction.withdrawal'); ?></th>
                    <th class="{{ $transaction['type'] == 'deposit' ? 'text-green' : 'text-red'}}">
                        <?php echo 'R$ ' . number_format($transaction['value'], 2, ',', ''); ?>
                    </th>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div