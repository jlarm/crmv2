<?php

use App\Models\Dealership;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {

    #[Computed]
    public function active()
    {
        return Dealership::activeStatusCount();
    }

    #[Computed]
    public function inActive()
    {
        return Dealership::inactiveStatusCount();
    }

    #[Computed]
    public function imported()
    {
        return Dealership::importedStatusCount();
    }
};
?>

@placeholder
<div class="grid auto-rows-min gap-2 md:grid-cols-3">
    <flux:skeleton animate="shimmer" class="h-20 w-full" />
    <flux:skeleton animate="shimmer" class="h-20 w-full" />
    <flux:skeleton animate="shimmer" class="h-20 w-full" />
</div>
@endplaceholder


<div class="grid auto-rows-min gap-2 md:grid-cols-3">
    <flux:card>
        <flux:subheading>Active Dealerships</flux:subheading>
        <flux:heading size="xl">{{ $this->active() }}</flux:heading>
    </flux:card>
    <flux:card>
        <flux:subheading>Inactive Dealerships</flux:subheading>
        <flux:heading size="xl">{{ $this->inActive() }}</flux:heading>
    </flux:card>
    <flux:card>
        <flux:subheading>Imported Dealerships</flux:subheading>
        <flux:heading size="xl">{{ $this->imported() }}</flux:heading>
    </flux:card>
</div>
