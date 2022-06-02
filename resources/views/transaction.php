{{-- Example with empty option (for Select) --}}
<x-adminlte-select name="optionsTest1">
    <x-adminlte-options :options="['Option 1', 'Option 2', 'Option 3']" disabled="1"
        empty-option="Select an option..."/>
</x-adminlte-select>

{{-- Example with placeholder (for Select) --}}
<x-adminlte-select name="optionsTest2">
    <x-adminlte-options :options="['Option 1', 'Option 2', 'Option 3']" disabled="1"
        placeholder="Select an option..."/>
</x-adminlte-select>

{{-- Example with empty option (for Select2) --}}
<x-adminlte-select2 name="optionsVehicles" igroup-size="lg" label-class="text-lightblue"
    data-placeholder="Select an option...">
    <x-slot name="prependSlot">
        <div class="input-group-text bg-gradient-info">
            <i class="fas fa-car-side"></i>
        </div>
    </x-slot>
    <x-adminlte-options :options="['Car', 'Truck', 'Motorcycle']" empty-option/>
</x-adminlte-select2>

{{-- Example with multiple selections (for Select) --}}
@php
    $options = ['s' => 'Spanish', 'e' => 'English', 'p' => 'Portuguese'];
    $selected = ['s','e'];
@endphp
<x-adminlte-select id="optionsLangs" name="optionsLangs[]" label="Languages"
    label-class="text-danger" multiple>
    <x-slot name="prependSlot">
        <div class="input-group-text bg-gradient-red">
            <i class="fas fa-lg fa-language"></i>
        </div>
    </x-slot>
    <x-adminlte-options :options="$options" :selected="$selected"/>
</x-adminlte-select>

{{-- Example with multiple selections (for SelectBs) --}}
@php
    $config = [
        "title" => "Select multiple options...",
        "liveSearch" => true,
        "liveSearchPlaceholder" => "Search...",
        "showTick" => true,
        "actionsBox" => true,
    ];
@endphp
<x-adminlte-select-bs id="optionsCategory" name="optionsCategory[]" label="Categories"
    label-class="text-danger" :config="$config" multiple>
    <x-slot name="prependSlot">
        <div class="input-group-text bg-gradient-red">
            <i class="fas fa-tag"></i>
        </div>
    </x-slot>
    <x-adminlte-options :options="['News', 'Sports', 'Science', 'Games']"/>
</x-adminlte-select-bs>