<?php

namespace App\Livewire\Public\Account;

use Livewire\Component;
use App\Models\CustomerAddress;
use Illuminate\Support\Collection;

class AddressManager extends Component
{
    public Collection $addresses;
    public bool $showForm = false;
    public ?int $editingAddressId = null;

    // Form fields
    public string $firstName = '';
    public string $lastName = '';
    public string $address = '';
    public string $addressLine2 = '';
    public string $city = '';
    public string $postalCode = '';
    public string $province = '';
    public string $phone = '';
    public string $country = 'IT';
    public bool $isDefault = false;

    protected $rules = [
        'firstName' => 'required|string|max:100',
        'lastName' => 'required|string|max:100',
        'address' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'postalCode' => 'required|string|regex:/^\d{5}$/',
        'province' => 'required|string|size:2',
        'phone' => 'required|string|regex:/^(\+39)?\d{9,10}$/',
        'country' => 'required|string|size:2',
    ];

    protected $messages = [
        'postalCode.regex' => 'Il CAP deve essere di 5 cifre.',
        'province.size' => 'La provincia deve essere di 2 caratteri (es. TO).',
        'phone.regex' => 'Inserisci un numero di telefono italiano valido.',
    ];

    public function mount(): void
    {
        $this->loadAddresses();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingAddressId = null;
    }

    public function edit(int $id): void
    {
        $address = CustomerAddress::where('customer_id', auth('customer')->id())
            ->findOrFail($id);

        $this->firstName = $address->first_name;
        $this->lastName = $address->last_name;
        $this->address = $address->address;
        $this->addressLine2 = $address->address_line_2 ?? '';
        $this->city = $address->city;
        $this->postalCode = $address->postal_code;
        $this->province = $address->province;
        $this->phone = $address->phone;
        $this->country = $address->country;
        $this->isDefault = $address->is_default;

        $this->editingAddressId = $id;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $customer = auth('customer')->user();
        
        if (!$customer) {
            return;
        }

        $data = [
            'customer_id' => $customer->id,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'address' => $this->address,
            'address_line_2' => $this->addressLine2 ?: null,
            'city' => $this->city,
            'postal_code' => $this->postalCode,
            'province' => strtoupper($this->province),
            'phone' => $this->phone,
            'country' => $this->country,
            'is_default' => $this->isDefault,
        ];

        if ($this->editingAddressId) {
            // Update existing
            $address = CustomerAddress::where('customer_id', $customer->id)
                ->findOrFail($this->editingAddressId);
            $address->update($data);
            
            $message = 'Indirizzo aggiornato con successo!';
        } else {
            // Create new
            CustomerAddress::create($data);
            $message = 'Indirizzo aggiunto con successo!';
        }

        // Se è default, rimuovi default dagli altri
        if ($this->isDefault) {
            CustomerAddress::where('customer_id', $customer->id)
                ->where('id', '!=', $this->editingAddressId)
                ->update(['is_default' => false]);
        }

        $this->loadAddresses();
        $this->cancelForm();
        
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => $message
        ]);
    }

    public function delete(int $id): void
    {
        $address = CustomerAddress::where('customer_id', auth('customer')->id())
            ->findOrFail($id);

        // Non permettere di cancellare l'ultimo indirizzo se è default
        if ($address->is_default && $this->addresses->count() > 1) {
            // Imposta un altro indirizzo come default
            $nextAddress = $this->addresses->where('id', '!=', $id)->first();
            if ($nextAddress) {
                $nextAddress->update(['is_default' => true]);
            }
        }

        $address->delete();
        $this->loadAddresses();
        
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Indirizzo eliminato.'
        ]);
    }

    public function setDefault(int $id): void
    {
        $customer = auth('customer')->user();
        
        // Rimuovi default da tutti
        CustomerAddress::where('customer_id', $customer->id)
            ->update(['is_default' => false]);
        
        // Imposta come default
        CustomerAddress::where('customer_id', $customer->id)
            ->where('id', $id)
            ->update(['is_default' => true]);

        $this->loadAddresses();
        
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Indirizzo predefinito aggiornato.'
        ]);
    }

    public function cancelForm(): void
    {
        $this->showForm = false;
        $this->editingAddressId = null;
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->firstName = '';
        $this->lastName = '';
        $this->address = '';
        $this->addressLine2 = '';
        $this->city = '';
        $this->postalCode = '';
        $this->province = '';
        $this->phone = '';
        $this->country = 'IT';
        $this->isDefault = false;
    }

    protected function loadAddresses(): void
    {
        $this->addresses = CustomerAddress::where('customer_id', auth('customer')->id())
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.public.account.address-manager');
    }
}