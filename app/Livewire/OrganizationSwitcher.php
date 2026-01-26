<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Organization;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

final class OrganizationSwitcher extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Computed]
    public function organizations(): Collection
    {
        return Auth::user()->organizations;
    }

    #[Computed]
    public function currentOrganization(): ?Organization
    {
        return Auth::user()->currentOrganization();
    }

    public function switchOrganization(int $organizationId): void
    {
        $user = Auth::user();

        if (! $user->belongsToOrganization($organizationId)) {
            return;
        }

        session(['current_organization_id' => $organizationId]);

        $this->redirect(url()->previous(), navigate: true);
    }

    public function createOrganization(): void
    {
        $this->validate();

        $slug = Str::slug($this->name);
        $originalSlug = $slug;
        $counter = 1;

        while (Organization::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        $organization = Organization::create([
            'name' => $this->name,
            'slug' => $slug,
        ]);

        Auth::user()->organizations()->attach($organization->id);

        session(['current_organization_id' => $organization->id]);

        $this->reset('name');

        Flux::modal('create-organization')->close();

        $this->redirect(url()->previous(), navigate: true);
    }
}
