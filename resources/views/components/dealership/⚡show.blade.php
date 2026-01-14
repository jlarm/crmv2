<?php

use App\Livewire\Forms\DealershipForm;
use App\Models\Dealership;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public DealershipForm $form;

    public function mount(Dealership $dealership): void
    {
        $this->form->setDealership($dealership);
    }

    #[Computed]
    public function users()
    {
        return User::query()
            ->orderBy('name')
            ->select('id', 'name')
            ->get();
    }
};
?>

<div>
    <form class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="col-span-2">
            <flux:card>
                <div class="space-y-6">
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

                    <flux:field>
                        <flux:label>Notes</flux:label>

                        <flux:textarea rows="auto" wire:model="form.notes" />

                        <flux:error name="form.phone"/>
                    </flux:field>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <flux:field>
                            <flux:label>Current Solution Name</flux:label>

                            <flux:input wire:model="form.currentSolutionName"/>

                            <flux:error name="form.currentSolutionName"/>
                        </flux:field>

                        <flux:field>
                            <flux:label>Current Solution Use</flux:label>

                            <flux:input wire:model="form.currentSolutionUse"/>

                            <flux:error name="form.currentSolutionUse"/>
                        </flux:field>
                    </div>
                </div>
            </flux:card>
            <div class="mt-5">
                <flux:button variant="primary">Save Changes</flux:button>
                <flux:button>Cancel</flux:button>
            </div>
        </div>
        <div class="space-y-4">
            <flux:card>
                <div class="grid gap-4">
                    <flux:heading size="lg">Consultants</flux:heading>
                    <flux:pillbox wire:model="form.consultants" multiple placeholder="Choose consultants...">
                        @foreach($this->users as $user)
                        <flux:pillbox.option :value="$user->id">{{ $user->name }}</flux:pillbox.option>
                        @endforeach
                    </flux:pillbox>
                </div>
            </flux:card>

            <flux:card>
                <div class="grid gap-4">
                    <flux:heading size="lg">Dealership Type</flux:heading>
                    <flux:select wire:model="form.type" placeholder="Choose type...">
                        <flux:select.option value="Automotive">Automotive</flux:select.option>
                        <flux:select.option value="RV">RV</flux:select.option>
                        <flux:select.option value="Motorsports">Motorsports</flux:select.option>
                        <flux:select.option value="Maritime">Maritime</flux:select.option>
                        <flux:select.option value="Association">Association</flux:select.option>
                    </flux:select>
                </div>
            </flux:card>

            <flux:card>
                <div class="grid gap-8">
                    <flux:heading size="lg">Dealership Status</flux:heading>
                    <flux:switch wire:model="form.inDevelopment" label="In Development"
                                 description="*Turn on In Development when actively working on this dealership with the Sales Dev Rep."/>
                    <flux:select label="Status" badge="required" wire:model="form.status"
                                 placeholder="Choose status...">
                        <flux:select.option value="active">Active</flux:select.option>
                        <flux:select.option value="inactive">Inactive</flux:select.option>
                        <flux:select.option value="imported">Imported</flux:select.option>
                    </flux:select>
                    <flux:select label="Rating" badge="required" wire:model="form.rating"
                                 placeholder="Choose rating...">
                        <flux:select.option value="hot">Hot</flux:select.option>
                        <flux:select.option value="warm">Warm</flux:select.option>
                        <flux:select.option value="cold">Cold</flux:select.option>
                    </flux:select>
                </div>
            </flux:card>
        </div>
    </form>
</div>
