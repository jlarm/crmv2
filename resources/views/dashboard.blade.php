<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-2 rounded-xl">
        <livewire:dealership.stats lazy />
        <div class="relative h-full flex-1 overflow-hidden">
            <livewire:dealership.index lazy />
        </div>
    </div>
</x-layouts.app>
