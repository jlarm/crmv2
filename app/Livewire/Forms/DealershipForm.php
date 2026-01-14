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

    public function setDealership(Dealership $dealership): void
    {
        $this->dealership = $dealership;
        $this->name = $dealership->name;
        $this->address = $dealership->address ?? '';
        $this->city = $dealership->city ?? '';
        $this->state = $dealership->state ?? '';
        $this->zipCode = $dealership->zip_code ?? '';
    }
}
