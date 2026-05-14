<?php

declare(strict_types=1);

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            // Indirizzo spedizione
            'shipping_first_name' => ['required', 'string', 'max:255'],
            'shipping_last_name' => ['required', 'string', 'max:255'],
            'shipping_email' => ['nullable', 'email', 'max:255'],
            'shipping_phone' => ['nullable', 'string', 'max:20'],
            'shipping_company' => ['nullable', 'string', 'max:255'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'shipping_city' => ['required', 'string', 'max:255'],
            'shipping_postal_code' => ['required', 'string', 'max:10'],
            'shipping_province' => ['required', 'string', 'size:2'],
            'shipping_country' => ['required', 'string', 'size:2'],
            
            // Indirizzo fatturazione (condizionali)
            'same_billing_as_shipping' => ['boolean'],
            'billing_first_name' => ['required_if:same_billing_as_shipping,false', 'string', 'max:255'],
            'billing_last_name' => ['required_if:same_billing_as_shipping,false', 'string', 'max:255'],
            'billing_email' => ['nullable', 'email', 'max:255'],
            'billing_phone' => ['nullable', 'string', 'max:20'],
            'billing_company' => ['nullable', 'string', 'max:255'],
            'billing_address' => ['required_if:same_billing_as_shipping,false', 'string', 'max:500'],
            'billing_city' => ['required_if:same_billing_as_shipping,false', 'string', 'max:255'],
            'billing_postal_code' => ['required_if:same_billing_as_shipping,false', 'string', 'max:10'],
            'billing_province' => ['required_if:same_billing_as_shipping,false', 'string', 'size:2'],
            'billing_country' => ['required_if:same_billing_as_shipping,false', 'string', 'size:2'],
            
            // Pagamento e altro
            'payment_method' => ['required', 'exists:payment_methods,code'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'accept_terms' => ['required', 'accepted'],
        ];
    }
    
    public function messages(): array
    {
        return [
            // Spedizione
            'shipping_first_name.required' => 'Il nome per la spedizione è obbligatorio.',
            'shipping_last_name.required' => 'Il cognome per la spedizione è obbligatorio.',
            'shipping_address.required' => 'L\'indirizzo di spedizione è obbligatorio.',
            'shipping_city.required' => 'La città di spedizione è obbligatoria.',
            'shipping_postal_code.required' => 'Il CAP di spedizione è obbligatorio.',
            'shipping_province.required' => 'La provincia di spedizione è obbligatoria.',
            'shipping_province.size' => 'La provincia deve essere di 2 caratteri.',
            'shipping_country.required' => 'Il paese di spedizione è obbligatorio.',
            
            // Fatturazione
            'billing_first_name.required_if' => 'Il nome per la fatturazione è obbligatorio.',
            'billing_last_name.required_if' => 'Il cognome per la fatturazione è obbligatorio.',
            'billing_address.required_if' => 'L\'indirizzo di fatturazione è obbligatorio.',
            'billing_city.required_if' => 'La città di fatturazione è obbligatoria.',
            'billing_postal_code.required_if' => 'Il CAP di fatturazione è obbligatorio.',
            'billing_province.required_if' => 'La provincia di fatturazione è obbligatoria.',
            
            // Generale
            'payment_method.required' => 'Seleziona un metodo di pagamento.',
            'payment_method.exists' => 'Il metodo di pagamento selezionato non è valido.',
            'accept_terms.required' => 'È necessario accettare i termini e condizioni.',
            'accept_terms.accepted' => 'È necessario accettare i termini e condizioni.',
        ];
    }
}