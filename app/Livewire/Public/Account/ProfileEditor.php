<?php

namespace App\Livewire\Public\Account;

use Livewire\Component;

class ProfileEditor extends Component
{
    public string $firstName = '';
    public string $lastName = '';
    public string $email = '';
    public string $phone = '';
    public bool $marketingConsent = false;

    protected $rules = [
        'firstName' => 'required|string|max:100',
        'lastName' => 'required|string|max:100',
        'phone' => 'nullable|string|max:20',
        'marketingConsent' => 'boolean',
    ];

    public function mount(): void
    {
        $customer = auth('customer')->user();
        
        if (!$customer) {
            return redirect('/account/login');
        }

        $this->firstName = $customer->first_name ?? '';
        $this->lastName = $customer->last_name ?? '';
        $this->email = $customer->email;
        $this->phone = $customer->phone ?? '';
        $this->marketingConsent = $customer->marketing_consent ?? false;
    }

    public function save(): void
    {
        $this->validate();

        $customer = auth('customer')->user();
        
        if (!$customer) {
            return;
        }

        $customer->update([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone' => $this->phone,
            'marketing_consent' => $this->marketingConsent,
        ]);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Profilo aggiornato con successo!'
        ]);
    }

    public function render()
    {
        return view('livewire.public.account.profile-editor');
    }
}