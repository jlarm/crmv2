<x-layouts.app :title="$dealership->name">
    <flux:card>
        <div class="flex justify-between items-center mb-5">
            <div class="flex gap-3 items-center">
                <flux:heading size="xl">{{ $dealership->name }}</flux:heading>
                <flux:text>ID: {{ $dealership->id }}</flux:text>
            </div>
            <flux:button wire:navigate :href="route('dashboard')" size="sm">Back</flux:button>
        </div>
        <flux:tab.group>
            <flux:tabs wire:model="tab">
                <flux:tab name="info">Info</flux:tab>
                <flux:tab name="stores">Stores</flux:tab>
                <flux:tab name="contacts">Contacts</flux:tab>
            </flux:tabs>

            <flux:tab.panel name="info">
                <livewire:dealership.show :$dealership lazy />
            </flux:tab.panel>
            <flux:tab.panel name="stores">
                <livewire:dealership.stores :$dealership lazy />
            </flux:tab.panel>
            <flux:tab.panel name="contacts">
                <livewire:dealership.contacts :$dealership lazy />
            </flux:tab.panel>
        </flux:tab.group>
    </flux:card>
</x-layouts.app>
