
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
                    <th> {{ $transaction['created_at_text'] }}</th>
                    @if ($admin)
                    <th> {{$transaction['account_id'] }} </th>
                    @endif
                    <th> {{ $transaction['type_text'] }}</th>
                    <th class="{{ $transaction['type'] == 'deposit' ? 'text-green' : 'text-red'}}">
                        {{ $transaction['value_text'] }}
                    </th>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div