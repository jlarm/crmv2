<?php

use App\Models\Dealership;
use Livewire\Component;

new class extends Component {
    public Dealership $dealership;
};
?>

<div x-data="{ tab: 'info' }">
    <div class="flex justify-between items-center mb-5">
        <div>
            <flux:text>ID: {{ $dealership->id }}</flux:text>
            <flux:heading size="xl">{{ $dealership->name }}</flux:heading>
        </div>
        <div>
            <flux:modal.trigger name="create-modal">
                <flux:button
                    x-show="tab === 'stores' || tab === 'contacts'"
                    x-cloak
                    size="sm"
                    variant="primary"
                >
                    Add <span x-text="tab === 'stores' ? 'Store' : 'Contact'"></span>
                </flux:button>
            </flux:modal.trigger>
            <flux:button wire:navigate :href="route('dashboard')" size="sm">Back</flux:button>
        </div>
    </div>
    <div>
        <flux:tab.group>
            <flux:tabs x-model="tab">
                <flux:tab name="info">Info</flux:tab>
                <flux:tab name="stores">Stores</flux:tab>
                <flux:tab name="contacts">Contacts</flux:tab>
            </flux:tabs>

            <flux:tab.panel name="info">
                <livewire:dealership.show :$dealership lazy/>
            </flux:tab.panel>
            <flux:tab.panel name="stores">
                <livewire:dealership.stores :$dealership lazy/>
            </flux:tab.panel>
            <flux:tab.panel name="contacts">
                <livewire:dealership.contacts :$dealership lazy/>
            </flux:tab.panel>
        </flux:tab.group>
    </div>

    <flux:modal name="create-modal" class="md:w-[500px]">
        <template x-if="tab === 'stores'">
            <livewire:dealership.create-store :$dealership lazy/>
        </template>
        <template x-if="tab === 'contacts'">
            <livewire:dealership.create-contact :$dealership lazy/>
        </template>
    </flux:modal>
</div>
