@props(['title', 'balance'])

<x-adminlte-card 
    class="
        mt-5 
        text-xl 
        text-center 
        {{ $balance == 0 ? 'text-blue' : '' }}
        {{ $balance > 0 ? 'text-green' : '' }}
        {{ $balance < 0 ? 'text-red' : '' }}
    "
    title="{{ $title }}" 
    theme="lightblue" 
    theme-mode="outline"
    icon="fas fa-lg fa-solid fa-money-bill" 
    header-class="text-uppercase rounded-bottom border-info"
>
    R$ {{ $balance }}
</x-adminlte-card>