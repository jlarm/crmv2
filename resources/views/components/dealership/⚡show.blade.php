<?php

use App\Livewire\Forms\DealershipForm;
use App\Models\Dealership;
use Livewire\Component;

new class extends Component {
    public DealershipForm $form;

    public function mount(Dealership $dealership): void
    {
        $this->form->setDealership($dealership);
    }
};
?>

<div>
    <form class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="col-span-2">
            <flux:card>
                <div class="space-y-5">
                    <flux:field>
                        <flux:label badge="Required">Name</flux:label>

                        <flux:input wire:model="form.name"/>

                        <flux:error name="name"/>
                    </flux:field>

                    <flux:field>
                        <flux:label>Address</flux:label>

                        <flux:input wire:model="form.address"/>

                        <flux:error name="address"/>
                    </flux:field>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <flux:field>
                            <flux:label>City</flux:label>

                            <flux:input wire:model="form.city"/>

                            <flux:error name="city"/>
                        </flux:field>
                        <flux:field>
                            <flux:label>State</flux:label>

                            <flux:input wire:model="form.state"/>

                            <flux:error name="state"/>
                        </flux:field>
                        <flux:field>
                            <flux:label>Zip Code</flux:label>

                            <flux:input wire:model="form.zipCode"/>

                            <flux:error name="zipCode"/>
                        </flux:field>
                    </div>

                    <flux:field>
                        <flux:label>Phone Number</flux:label>

                        <flux:input mask="(999) 999-9999" wire:model="form.phone"/>

                        <flux:error name="form.phone"/>
                    </flux:field>
                </div>
            </flux:card>
            <div class="mt-5">
                <flux:button variant="primary">Save Changes</flux:button>
                <flux:button>Cancel</flux:button>
            </div>
        </div>
        <div>
            <flux:card>
                test
            </flux:card>
        </div>
    </form>
</div>
