@extends('adminlte::page')

@section('title',  env('BANK_NAME', 'Apollo') . ' Bank')

@section('content_header')
    <h1>{{ __('transaction.add_transfer') }}</h1>
@stop

@section('content')
    <x-guest-layout>

        <x-auth-card class="mt-0">
            <x-slot name="logo" >
                <!-- The use of this slot is required by the component -->
            </x-slot>

            <x-balance-card 
                :title="__('account.available_balance')"
                :balance="$data['account']['balance']" 
            />

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="post" class="mt-8">
                @csrf

                <!-- User -->
                <div>
                    <x-label for="user" :value="__('transaction.transfer_to')" />
                    <x-select 
                        id="user"
                        name="user"
                        class="block mt-1 w-full"
                        :value="-1"
                        required
                    >
                        @if (count($data['clients']) > 0)
                            @for ($i = 0; $i < count($data['clients']); $i++)
                                <option value="{{ $data['clients'][$i]['user_id'] }}">
                                    {{ $data['clients'][$i]['name'] }}
                                </option>
                            @endfor
                        @endif
                    </x-select>
                </div>

                <!-- Name -->
                <div class="mt-6">
                    <x-label for="value" :value="__('transaction.value')" />
                    <x-input 
                        id="value" 
                        name="value" 
                        class="block mt-1 w-full" 
                        type="number" 
                        step="0.01"
                        required 
                        autofocus
                        autocomplete="off" 
                    />
                </div>

                <div class="flex items-center justify-center mt-8">
                    <x-button class="ml-4">
                        {{ __('transaction.add_transfer') }}
                    </x-button>
                </div>
            </form>
        </x-auth-card>
    </x-guest-layout>
@stop

@section('css')
    <style></style>
@stop

@section('js')
    <script></script>
@stop