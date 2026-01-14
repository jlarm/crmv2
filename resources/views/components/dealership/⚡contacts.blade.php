<?php

use App\Models\Dealership;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public Dealership $dealership;

    #[Computed]
    public function contacts()
    {
        return $this->dealership
            ->contacts()
            ->get();
    }
};
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    @foreach($this->contacts as $contact)
        <flux:card class="hover:bg-slate-50 transition space-y-4">
            <div class="flex items-center justify-between gap-2">
                <flux:heading size="lg">{{ $contact->name }}</flux:heading>
                <flux:badge size="sm">{{ $contact->position }}</flux:badge>
            </div>
            <div>
                @if($contact->phone)
                <span class="flex items-center gap-2 text-sm"><flux:icon.phone class="size-3" /> {{ $contact->phone }}</span>
                @endif
                @if($contact->email)
                <span class="flex items-center gap-2 text-sm"><flux:icon.envelope class="size-3" /> {{ $contact->email }}</span>
                @endif
                @if($contact->linkedin_link)
                <a :href="$contact->linkedin_link" class="flex items-center gap-2 text-sm"><flux:icon.linkedin class="size-3" />LinkedIn</a>
                @endif
            </div>
        </flux:card>
    @endforeach
</div>
