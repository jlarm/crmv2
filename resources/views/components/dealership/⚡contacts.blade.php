<?php

use App\Models\Contact;
use App\Models\Dealership;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public Dealership $dealership;

    #[Computed]
    #[On('contact-created')]
    #[On('contact-deleted')]
    public function contacts()
    {
        return $this->dealership
            ->contacts()
            ->orderByDesc('primary_contact')
            ->orderBy('name')
            ->get();
    }

    public function delete(int $contactId): void
    {
        $contact = Contact::findOrFail($contactId);

        $contact->delete();

        Flux::toast(
            text: 'Contact deleted successfully',
            variant: 'success',
        );

        $this->dispatch('contact-delete');
    }
};
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    @forelse($this->contacts as $contact)
        <flux:card class="relative hover:bg-slate-50 transition space-y-4">
            <div class="flex items-center justify-between gap-2">
                <flux:heading class="flex items-center gap-2">
                    {{ $contact->name }}
                    @if($contact->primary_contact)
                        <flux:tooltip content="Primary Contact">
                            <flux:icon.star variant="micro" class="text-amber-500 dark:text-amber-300"/>
                        </flux:tooltip>
                    @endif
                </flux:heading>
                <flux:dropdown position="bottom" align="end">
                    <flux:button variant="ghost" size="xs" icon="ellipsis-vertical" inset="top right bottom"/>

                    <flux:navmenu>
                        <flux:navmenu.item href="#" icon="pencil">Edit</flux:navmenu.item>
                        <flux:navmenu.item wire:confirm="Are you sure you want to delete this contact?"
                                           wire:click="delete({{ $contact->id }})" icon="trash" variant="danger">Delete
                        </flux:navmenu.item>
                    </flux:navmenu>
                </flux:dropdown>
            </div>
            <div class="text-zinc-500">
                @if($contact->position)
                    <flux:badge size="sm" color="blue" class="mb-2">{{ $contact->position }}</flux:badge>
                @endif
                @if($contact->phone)
                    <span class="flex items-center gap-2 text-sm"><flux:icon.phone class="size-3"/> {{ $contact->phone }}</span>
                @endif
                @if($contact->email)
                    <span class="flex items-center gap-2 text-sm"><flux:icon.envelope class="size-3"/> {{ $contact->email }}</span>
                @endif
                @if($contact->linkedin_link)
                    <a href="{{ $contact->linkedin_link }}" class="flex items-center gap-2 text-sm">
                        <flux:icon.linkedin class="size-3"/>
                        LinkedIn</a>
                @endif
            </div>
        </flux:card>
    @empty
        <div class="col-span-full text-center">
            <flux:heading size="lg">No contacts found.</flux:heading>
        </div>
    @endforelse
</div>
