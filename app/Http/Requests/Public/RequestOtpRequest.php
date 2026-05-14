<?php

declare(strict_types=1);

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class RequestOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'accept_terms' => ['required', 'accepted'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'email.required' => 'L\'indirizzo email è obbligatorio.',
            'email.email' => 'Inserisci un indirizzo email valido.',
            'email.max' => 'L\'indirizzo email non può superare i 255 caratteri.',
            'accept_terms.required' => 'È necessario accettare i termini e condizioni.',
            'accept_terms.accepted' => 'È necessario accettare i termini e condizioni.',
        ];
    }
}