<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProveedorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('ruc')) {
            $this->merge([
                'ruc' => trim((string) $this->ruc),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'ruc' => ['required', 'digits:11', 'unique:proveedores,ruc'],
            'razon_social' => ['required', 'max:120'],
            'telefono' => ['required', 'digits_between:7,15'],
            'email' => ['required', 'email', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'ruc.required' => 'El RUC es obligatorio.',
            'ruc.digits' => 'El RUC debe tener exactamente 11 dígitos numéricos.',
            'ruc.unique' => 'Ya existe un proveedor registrado con ese RUC.',
            'razon_social.required' => 'La razón social es obligatoria.',
            'razon_social.max' => 'La razón social admite como máximo 120 caracteres.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits_between' => 'El teléfono solo admite números (entre 7 y 15 dígitos).',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.max' => 'El correo admite como máximo 100 caracteres.',
        ];
    }
}