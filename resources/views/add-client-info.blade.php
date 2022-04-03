<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('main.add_client_info_message') }}
        </div>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form>
            @csrf

            <!-- Name -->
            <div>
                <x-label for="name" :value="__('main.full_name')" />
                <x-input 
                    id="name" 
                    class="block mt-1 w-full" 
                    type="text" 
                    name="name" 
                    :value="old('name')" 
                    required 
                    autofocus 
                />
            </div>

            <!-- CPF -->
            <div class="mt-4">
                <x-label for="cpf" :value="__('main.cpf')" />
                <x-input 
                    id="cpf" 
                    class="block mt-1 w-full" 
                    type="text" 
                    name="cpf" 
                    :value="old('cpf')" 
                    required 
                    autofocus
                    placeholder="XXX.XXX.XXX-XX"
                    pattern="[0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2}" 
                />
            </div>

            <!-- Phone -->
            <div class="mt-4">
                <x-label for="phone" :value="__('main.phone')" />
                <x-input 
                    id="phone" 
                    class="block mt-1 w-full" 
                    type="text" 
                    name="phone" 
                    :value="old('phone')" 
                    required 
                    autofocus
                    minlength="13"
                    maxlength="13"
                />
            </div>

            <!-- Birth Date -->
            <div class="mt-4">
                <x-label for="birth_date" :value="__('main.birth_date')" />
                <x-input 
                    id="birth_date" 
                    class="block mt-1 w-full" 
                    type="date" 
                    name="birth_date" 
                    :value="old('birth_date')" 
                    required 
                    autofocus
                />
            </div>

            <div class="mt-4 flex">
                 <!-- City -->
                <div>
                    <x-label for="city" :value="__('main.city')" />
                    <x-input 
                        id="city" 
                        class="block mt-1 w-full" 
                        type="text" 
                        name="city" 
                        :value="old('city')" 
                        required 
                        autofocus
                    />
                </div>
                <div class="ml-4">
                    <!-- UF -->
                    <x-label for="uf" :value="__('main.uf')" />
                    <x-input 
                        id="uf" 
                        class="block mt-1 w-full" 
                        type="text" 
                        name="uf" 
                        :value="old('uf')" 
                        required 
                        autofocus
                        minlength="2"
                        maxlength="2"
                    />
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4">
                    {{ __('auth.register') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
