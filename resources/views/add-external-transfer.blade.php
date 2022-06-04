@extends('adminlte::page')

@section('title', 'Apollo Banke')

@section('content_header')
    <h1>{{ __('transaction.add_external_transfer') }}</h1>
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

                <!-- Bank -->
                <div>
                    <x-label for="bank" :value="__('transaction.institution')" />
                    <x-select 
                        id="bank"
                        name="bank"
                        class="block mt-1 w-full"
                        :value="-1"
                        required
                    >
                        @if (count($data['banks']) > 0)
                            @for ($i = 0; $i < count($data['banks']); $i++)
                                <option value="{{ $data['banks'][$i]['id'] }}">
                                    {{ $data['banks'][$i]['name'] }}
                                </option>
                            @endfor
                        @endif
                    </x-select>
                </div>

                <!-- Account -->
                <div class="mt-6">
                    <x-label for="account" :value="__('transaction.external_account')" />
                    <x-input 
                        id="account" 
                        name="account" 
                        class="block mt-1 w-full" 
                        type="number" 
                        required 
                        autofocus
                        autocomplete="off" 
                    />
                </div>

                <!-- Value -->
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
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@stop

@section('js')
    <script></script>
@stop