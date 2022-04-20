@extends('adminlte::page')

@section('title', 'Apollo Banke')

@section('content_header')
    <h1>{{ __('transaction.add_' . $data['type']) }}</h1>
@stop

@section('content')
    <x-guest-layout>
        <x-auth-card>
            <x-slot name="logo" >
                <!-- The use of this slot is required by the component -->
            </x-slot>

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="post">
                @csrf

                <!-- Type -->
                <x-input 
                    id="type"
                    hidden 
                    type="hidden" 
                    step="0.01"
                    name="type" 
                    :value="$data['type']" 
                    required 
                    autofocus
                    autocomplete="off" 
                />

                <!-- Name -->
                <div>
                    <x-label for="value" :value="__('transaction.value')" />
                    <x-input 
                        id="value" 
                        class="block mt-1 w-full" 
                        type="number" 
                        step="0.01"
                        name="value" 
                        :value="old('value')" 
                        required 
                        autofocus
                        autocomplete="off" 
                    />
                </div>

                <div class="flex items-center justify-center mt-8">
                    <x-button class="ml-4">
                    {{ __('transaction.add_' . $data['type']) }}
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