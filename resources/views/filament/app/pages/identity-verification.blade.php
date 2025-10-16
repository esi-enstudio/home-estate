<x-filament-panels::page>
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        @if(!in_array(auth()->user()->identity_status, ['approved', 'pending']))
            <x-filament::button type="submit" class="mt-4">
                জমা দিন
            </x-filament::button>
        @endif
    </form>
</x-filament-panels::page>
