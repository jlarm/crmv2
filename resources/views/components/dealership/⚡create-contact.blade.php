<?php

use App\Models\Dealership;
use Livewire\Component;

new class extends Component {
    public Dealership $dealership;
};
?>

<div>
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Create Contact</flux:heading>
        </div>
        <flux:input label="Name" placeholder="Your name"/>
        <flux:input label="Date of birth" type="date"/>
        <div class="flex">
            <flux:spacer/>
            <flux:button type="submit" variant="primary">Save changes</flux:button>
        </div>
    </div>
</div>
