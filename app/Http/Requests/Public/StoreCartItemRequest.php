<?php

declare(strict_types=1);

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'variant_id' => ['required', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'variant_id.required' => 'Seleziona una variante del prodotto.',
            'variant_id.exists' => 'La variante selezionata non è valida.',
            'quantity.required' => 'La quantità è obbligatoria.',
            'quantity.integer' => 'La quantità deve essere un numero intero.',
            'quantity.min' => 'La quantità minima è 1.',
            'quantity.max' => 'La quantità massima è 100.',
        ];
    }
}