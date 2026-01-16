<?php

use App\Livewire\Forms\StoreForm;
use App\Models\Dealership;
use Livewire\Component;

new class extends Component {
    public Dealership $dealership;

    public StoreForm $form;

    public function mount(Dealership $dealership): void
    {
        $this->dealership = $dealership;
        $this->form->dealership = $dealership;
    }

    public function save(): void
    {
        $this->form->save();

        $this->dispatch('store-created');

        Flux::toast(text: 'Store created successfully', variant: 'success');

        Flux::modal('create-modal')->close();
    }
};
?>

<div>
    <form wire:submit.prevent="save" class="space-y-6">
        <div>
            <flux:heading size="lg">Create Store</flux:heading>
        </div>
        <flux:input wire:model="form.name" label="Name"/>
        <flux:input wire:model="form.address" label="Address"/>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:input wire:model="form.city" label="City"/>
            <flux:input wire:model="form.state" label="State"/>
            <flux:input wire:model="form.zipCode" label="Zip Code"/>
        </div>
        <flux:input wire:model="form.phone" label="Phone Number" type="tel" mask="(999) 999-9999"/>
        <flux:textarea wire:model="form.notes" label="Notes"/>
        <div class="flex">
            <flux:spacer/>
            <flux:button type="submit" variant="primary">Create</flux:button>
        </div>
    </form>
</div>
