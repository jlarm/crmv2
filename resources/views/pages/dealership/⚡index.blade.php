<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Dashboard')]
class extends Component {
    //
};
?>

<div>
    <div class="flex h-full w-full flex-1 flex-col gap-2 rounded-xl">
        @island(lazy: true, name: 'dealership-stats')
        <livewire:dealership.stats lazy/>
        @endisland
        <div class="relative h-full flex-1 overflow-hidden">
            @island(lazy: true, name: 'dealership-index')
            <livewire:dealership.index lazy/>
            @endisland
        </div>
    </div>
</div>
