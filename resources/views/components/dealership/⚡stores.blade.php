<?php

use App\Models\Dealership;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public Dealership $dealership;

    #[Computed]
    public function stores()
    {
        return $this->dealership
            ->stores()
            ->get();
    }
};
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    @foreach($this->stores as $store)
        <flux:card class="hover:bg-slate-50 transition">
            <flux:heading>{{ $store->name }}</flux:heading>
            <flux:text class="mt-1">
                {{ $store->address }}<br />
                {{ $store->city }}, {{ $store->state }} {{ $store->zip_code }}
                <span class="block mt-3">{{ $store->phone }}</span>
            </flux:text>
        </flux:card>
    @endforeach
</div>
