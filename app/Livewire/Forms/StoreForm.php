<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Dealership;
use Livewire\Attributes\Validate;
use Livewire\Form;

final class StoreForm extends Form
{
    public ?Dealership $dealership = null;

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

    #[Validate('nullable|string')]
    public string $notes = '';

    public function save()
    {
        $this->validate();

        return $this->dealership->stores()->create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zipCode,
            'phone' => $this->phone,
            'notes' => $this->notes,
        ]);
    }
}
