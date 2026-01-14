<x-layouts.app :title="$dealership->name">
    <div class="flex justify-between items-center">
        <div>
            <flux:heading size="xl">{{ $dealership->name }}</flux:heading>
            <flux:text>ID: {{ $dealership->id }}</flux:text>
        </div>
        <flux:button wire:navigate :href="route('dashboard')" size="sm">Back</flux:button>
    </div>
    <div class="my-4">
        <flux:navbar>
            <flux:navbar.item :href="route('dealership.show', $dealership)">Info</flux:navbar.item>
            <flux:navbar.item href="#">Stores</flux:navbar.item>
            <flux:navbar.item href="#">Contacts</flux:navbar.item>
            <flux:navbar.item href="#">Progress</flux:navbar.item>
            <flux:navbar.item href="#">Emails</flux:navbar.item>
        </flux:navbar>
        <flux:separator variant="subtle" />
    </div>
    <div>
        <livewire:dealership.show :$dealership />
    </div>
</x-layouts.app>
