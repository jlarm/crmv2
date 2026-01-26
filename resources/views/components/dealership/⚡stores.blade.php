<?php

use App\Models\Dealership;
use App\Models\Store;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public Dealership $dealership;

    #[Computed]
    #[On('store-created')]
    #[On('store-deleted')]
    public function stores()
    {
        return $this->dealership
            ->stores()
            ->get();
    }

    public function delete(int $storeId): void
    {
        $store = Store::findOrFail($storeId);

        $store->delete();

        Flux::toast(
            text: 'Store deleted successfully',
            variant: 'success',
        );

        $this->dispatch('store-deleted');
    }
};
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    @forelse($this->stores as $store)
        <flux:card class="hover:bg-slate-50 transition">
            <div class="flex items-center justify-between">
                <flux:heading>{{ $store->name }}</flux:heading>
                <flux:dropdown position="bottom" align="end">
                    <flux:button variant="ghost" size="xs" icon="ellipsis-vertical" inset="top right bottom"/>

                    <flux:navmenu>
                        <flux:navmenu.item href="#" icon="pencil">Edit</flux:navmenu.item>
                        <flux:navmenu.item wire:confirm="Are you sure you want to delete this contact?"
                                           wire:click="delete({{ $store->id }})" icon="trash" variant="danger">Delete
                        </flux:navmenu.item>
                    </flux:navmenu>
                </flux:dropdown>
            </div>
            <flux:text class="mt-1">
                {{ $store->address }}<br/>
                {{ $store->city }}, {{ $store->state }} {{ $store->zip_code }}
                <span class="block mt-3">{{ $store->phone }}</span>
            </flux:text>
        </flux:card>
    @empty
        <div class="col-span-full text-center">
            <flux:heading size="lg">No stores found.</flux:heading>
        </div>
    @endforelse
</div>
