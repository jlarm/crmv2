<?php

use App\Models\Dealership;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $sortBy = 'name';
    public string $sortDirection = 'asc';
    public string $search = '';
    public array $selectedUsers = [];
    public array $selectedStatuses = [];
    public array $selectedRatings = [];

    public function sort($column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->selectedUsers = [];
        $this->selectedStatuses = [];
        $this->selectedRatings = [];
    }

    #[Computed]
    public function dealerships()
    {
        return Dealership::query()
            ->select('id', 'name', 'city', 'state')
            ->when($this->selectedUsers, fn($query) => $query->whereHas('users', fn($q) => $q->whereIn('users.id', $this->selectedUsers)))
            ->when($this->selectedStatuses, fn($query) => $query->whereIn('status', $this->selectedStatuses))
            ->when($this->selectedRatings, fn($query) => $query->whereIn('rating', $this->selectedRatings))
            ->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->tap(fn($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->paginate(15);
    }

    #[Computed]
    public function users()
    {
        return User::query()
            ->select('id', 'name')
            ->get();
    }
};
?>

<div>
    <div class="w-full flex justify-between items-center gap-x-2">
        <flux:input icon="magnifying-glass" placeholder="Search dealerships..." wire:model.live.debounce.500ms="search"/>
        <flux:pillbox multiple placeholder="Filter by user..." wire:model.live="selectedUsers">
            @foreach($this->users as $user)
                <flux:pillbox.option :key="$user->id" :value="$user->id">{{ $user->name }}</flux:pillbox.option>
            @endforeach
        </flux:pillbox>
        <flux:pillbox multiple placeholder="Filter by status..." wire:model.live="selectedStatuses">
            <flux:pillbox.option value="active">Active</flux:pillbox.option>
            <flux:pillbox.option value="inactive">Inactive</flux:pillbox.option>
            <flux:pillbox.option value="imported">Imported</flux:pillbox.option>
        </flux:pillbox>
        <flux:pillbox multiple placeholder="Filter by rating..." wire:model.live="selectedRatings">
            <flux:pillbox.option value="hot">Hot</flux:pillbox.option>
            <flux:pillbox.option value="warm">Warm</flux:pillbox.option>
            <flux:pillbox.option value="cold">Cold</flux:pillbox.option>
        </flux:pillbox>
        <flux:button wire:click="clearFilters">Clear Filters</flux:button>
    </div>
    <flux:table class="w-full" :paginate="$this->dealerships">
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Name</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach($this->dealerships as $dealership)
                <flux:table.row :key="$dealership->id">
                    <flux:table.cell>
                        {{ $dealership->name }}
                        <p class="text-xs text-zinc-400">{{ $dealership->city }}, {{ $dealership->state }}</p>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
