<?php

declare(strict_types=1);

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class ApplyCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:64', 'uppercase'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'code.required' => 'Il codice coupon è obbligatorio.',
            'code.string' => 'Il codice coupon deve essere una stringa.',
            'code.max' => 'Il codice coupon non può superare i 64 caratteri.',
            'code.uppercase' => 'Il codice coupon deve essere in maiuscolo.',
        ];
    }
    
    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper($this->code),
        ]);
    }
}