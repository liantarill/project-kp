{{-- <x-filament-panels::page> --}}
{{-- Page content --}}

{{-- <x-filament::card class="mb-6">
        {{ $this->form }}
    </x-filament::card> --}}

{{-- </x-filament-panels::page> --}}

<x-filament::page>
    {{-- Filter Form --}}
    <div class="mb-6">
        <form wire:submit.prevent="$refresh">
            {{ $this->form }}
        </form>
    </div>


</x-filament::page>
