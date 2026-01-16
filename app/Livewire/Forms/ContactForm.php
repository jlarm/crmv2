<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Dealership;
use Livewire\Attributes\Validate;
use Livewire\Form;

final class ContactForm extends Form
{
    public ?Dealership $dealership = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|max:255')]
    public string $phone = '';

    #[Validate('required|string|max:255')]
    public string $position = '';

    #[Validate('required|url|max:255')]
    public string $linkedinLink = '';

    #[Validate('required|boolean')]
    public bool $primaryContact = false;

    public function save()
    {
        $this->validate();

        return $this->dealership->contacts()->create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'position' => $this->position,
            'linkedin_link' => $this->linkedinLink,
            'primary_contact' => $this->primaryContact,
        ]);
    }
}
