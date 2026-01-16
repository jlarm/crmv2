<?php

use App\Livewire\Forms\ContactForm;
use App\Models\Dealership;
use Livewire\Component;

new class extends Component {
    public ContactForm $form;

    public function mount(Dealership $dealership): void
    {
        $this->$dealership = $dealership;
        $this->form->dealership = $dealership;
    }

    public function save(): void
    {
        $this->form->save();

        $this->dispatch('contact-created');

        Flux::toast(text: 'Contact created successfully', variant: 'success');

        Flux::modal('create-modal')->close();
    }
};
?>

<div>
    <form wire:submit.prevent="save" class="space-y-6">
        <div>
            <flux:heading size="lg">Create Contact</flux:heading>
        </div>
        <flux:input wire:model="form.name" label="Name" />
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input wire:model="form.email" label="Email" type="email" />
            <flux:input wire:model="form.phone" label="Phone Number" type="tel" mask="(999) 999-9999"/>
        </div>
        <flux:input wire:model="form.position" label="Position" />
        <flux:input wire:model="form.linkedinLink" label="LinkedIn Profile" type="url" />
        <flux:switch wire:model="form.primaryContact" label="Primary Contact" />
        <div class="flex">
            <flux:spacer/>
            <flux:button type="submit" variant="primary">Save changes</flux:button>
        </div>
    </form>
</div>
