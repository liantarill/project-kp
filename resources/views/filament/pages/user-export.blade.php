<x-filament::page>
    <div class="mb-6">
        <form wire:submit.prevent="resetTable">
            {{ $this->form }}
        </form>
    </div>

    <div>
        {{ $this->table }}
    </div>
</x-filament::page>
