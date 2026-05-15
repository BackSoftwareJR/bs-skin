<?php

declare(strict_types=1);

namespace App\Livewire\Public\Account;

use Livewire\Component;

class ProfileEditor extends Component
{
    public string $name = '';
    public string $surname = '';
    public string $email = '';
    public string $phone = '';
    public bool $marketingConsent = false;

    protected $rules = [
        'name'             => 'required|string|max:100',
        'surname'          => 'required|string|max:100',
        'phone'            => 'nullable|string|max:20',
        'marketingConsent' => 'boolean',
    ];

    protected $messages = [
        'name.required'    => 'Il nome è obbligatorio.',
        'surname.required' => 'Il cognome è obbligatorio.',
    ];

    public function mount(): mixed
    {
        $customer = auth('customer')->user();

        if (!$customer) {
            return redirect('/account/login');
        }

        $this->name             = $customer->name ?? '';
        $this->surname          = $customer->surname ?? '';
        $this->email            = $customer->email;
        $this->phone            = $customer->phone ?? '';
        $this->marketingConsent = $customer->marketing_consent ?? false;

        return null;
    }

    public function save(): void
    {
        $this->validate();

        $customer = auth('customer')->user();

        if (!$customer) {
            return;
        }

        $customer->update([
            'name'               => $this->name,
            'surname'            => $this->surname,
            'phone'              => $this->phone ?: null,
            'marketing_consent'  => $this->marketingConsent,
        ]);

        $this->dispatch('toast', [
            'type'    => 'success',
            'message' => 'Profilo aggiornato con successo!',
        ]);
    }

    public function render()
    {
        return view('livewire.public.account.profile-editor');
    }
}
