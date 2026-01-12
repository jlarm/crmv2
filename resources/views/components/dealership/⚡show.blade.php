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
                </div>
            </flux:card>
            <div class="mt-5">
                <flux:button variant="primary">Save Changes</flux:button>
                <flux:button>Cancel</flux:button>
            </div>
        </div>
        <div>
            <flux:card>
                <flux:tab.group>
                    <flux:tabs wire:model="tab">
                        <flux:tab name="profile">Profile</flux:tab>
                        <flux:tab name="account">Account</flux:tab>
                        <flux:tab name="billing">Billing</flux:tab>
                    </flux:tabs>

                    <flux:tab.panel name="profile">profile</flux:tab.panel>
                    <flux:tab.panel name="account">account</flux:tab.panel>
                    <flux:tab.panel name="billing">billing</flux:tab.panel>
                </flux:tab.group>
            </flux:card>
        </div>
    </form>
</div>
