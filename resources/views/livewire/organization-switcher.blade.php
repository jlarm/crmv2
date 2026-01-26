<div>
    @php
        $organizations = $this->organizations;
        $currentOrganization = $this->currentOrganization;
    @endphp

    <flux:dropdown position="bottom" align="start">
        <flux:button variant="subtle" icon:trailing="chevron-down">
            {{ $currentOrganization?->name ?? 'Select Organization' }}
        </flux:button>

        <flux:menu>
            @foreach ($organizations as $organization)
                <flux:menu.item
                    wire:click="switchOrganization({{ $organization->id }})"
                    :icon="$currentOrganization && $organization->id === $currentOrganization->id ? 'check' : null"
                >
                    {{ $organization->name }}
                </flux:menu.item>
            @endforeach

            @if ($organizations->isNotEmpty())
                <flux:menu.separator />
            @endif

            <flux:modal.trigger name="create-organization">
                <flux:menu.item icon="plus">
                    {{ __('Create Organization') }}
                </flux:menu.item>
            </flux:modal.trigger>
        </flux:menu>
    </flux:dropdown>

    <flux:modal name="create-organization" class="md:w-96">
        <form wire:submit="createOrganization" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Create Organization') }}</flux:heading>
            </div>

            <flux:input
                wire:model="name"
                label="{{ __('Organization Name') }}"
                placeholder="{{ __('Enter organization name') }}"
            />

            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">{{ __('Create') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
