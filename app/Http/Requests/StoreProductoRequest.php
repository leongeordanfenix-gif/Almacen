<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'codigo' => ['required', 'max:20', 'alpha_dash', 'unique:productos,codigo'],
            'nombre' => ['required', 'max:100'],
            'descripcion' => ['required', 'max:500'],
            'precio' => ['required', 'numeric', 'gt:0', 'max:999999.99', 'decimal:0,2'],
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'El código es obligatorio.',
            'codigo.max' => 'El código admite como máximo 20 caracteres.',
            'codigo.alpha_dash' => 'El código solo admite letras, números y guiones.',
            'codigo.unique' => 'Ya existe un producto con ese código.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre admite como máximo 100 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.max' => 'La descripción admite como máximo 500 caracteres.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.gt' => 'El precio debe ser mayor que cero.',
            'precio.max' => 'El precio no puede superar 999999.99.',
            'precio.decimal' => 'El precio admite como máximo 2 decimales.',
        ];
    }
}