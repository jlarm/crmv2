<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Dealership;
use Livewire\Attributes\Validate;
use Livewire\Form;

final class DealershipForm extends Form
{
    public ?Dealership $dealership;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:255')]
    public string $address = '';

    #[Validate('nullable|string|max:255')]
    public string $city = '';

    #[Validate('nullable|string|max:255')]
    public string $state = '';

    #[Validate('nullable|string|max:255')]
    public string $zipCode = '';

    #[Validate('nullable|string|max:255')]
    public string $phone = '';

    #[Validate('nullable|string|max:255')]
    public string $type = '';

    #[Validate('boolean')]
    public bool $inDevelopment = false;

    #[Validate('nullable|string|max:255')]
    public string $status = '';

    #[Validate('nullable|string|max:255')]
    public string $rating = '';

    public array $consultants = [];

    #[Validate('nullable|string')]
    public string $notes = '';

    #[Validate('nullable|string|max:255')]
    public string $currentSolutionName = '';

    #[Validate('nullable|string|max:255')]
    public string $currentSolutionUse = '';

    public function setDealership(Dealership $dealership): void
    {
        $this->dealership = $dealership;
        $this->name = $dealership->name;
        $this->address = $dealership->address ?? '';
        $this->city = $dealership->city ?? '';
        $this->state = $dealership->state ?? '';
        $this->zipCode = $dealership->zip_code ?? '';
        $this->phone = $dealership->phone ?? '';
        $this->type = $dealership->type ?? '';
        $this->inDevelopment = $dealership->in_development;
        $this->status = $dealership->status ?? '';
        $this->rating = $dealership->rating ?? '';
        $this->notes = $dealership->notes ?? '';
        $this->currentSolutionName = $dealership->current_solution_name ?? '';
        $this->currentSolutionUse = $dealership->current_solution_use ?? '';
        $this->consultants = $dealership->users->pluck('id')->toArray();
    }

    public function update(): void
    {
        $this->validate();

        $this->dealership->update([
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zipCode,
            'phone' => $this->phone,
            'type' => $this->type,
            'in_development' => $this->inDevelopment,
            'status' => $this->status,
            'rating' => $this->rating,
            'notes' => $this->notes,
            'current_solution_name' => $this->currentSolutionName,
            'current_solution_use' => $this->currentSolutionUse,
        ]);

        $this->dealership->users()->sync($this->consultants);
    }

    public function delete()
    {
        $this->dealership->delete();
    }
}
