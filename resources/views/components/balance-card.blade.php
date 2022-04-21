@props(['balance'])

<x-adminlte-card 
    class="mt-5 text-xl text-center"
    title="{{ __('account.available_balance') }}" 
    theme="lightblue" 
    theme-mode="outline"
    icon="fas fa-lg fa-solid fa-money-bill" 
    header-class="text-uppercase rounded-bottom border-info"
>
    {{ $balance }}
</x-adminlte-card>