<?php

use App\Models\Proveedor;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public ?Proveedor $proveedor = null;
    public $ruc = '';
    public $razon_social = '';
    public $telefono = '';
    public $email = '';

    protected function rules(): array
    {
        return [
            'ruc' => [
                'required', 'digits:11',
                Rule::unique('proveedores', 'ruc')->ignore($this->proveedor?->id)
            ],
            'razon_social' => ['required', 'max:120'],
            'telefono' => ['required', 'digits_between:7,15'],
            'email' => ['required', 'email', 'max:100'],
        ];
    }

    protected function messages(): array
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

    public function updated($propiedad)
    {
        if ($propiedad === 'ruc') {
            $this->ruc = trim((string) $this->ruc);
        }
        $this->validateOnly($propiedad);
    }

    public function guardar()
    {
        $datos = $this->validate();

        if ($this->proveedor) {
            $this->proveedor->update($datos);
        } else {
            Proveedor::create($datos);
        }

        $this->cancelar();
        session()->flash('ok', 'Proveedor guardado correctamente.');
        $this->dispatch('proveedor-guardado');
    }

    public function cancelar()
    {
        $this->reset();
        $this->resetValidation();
    }

    #[On('editar-proveedor')]
    public function editar($id)
    {
        $this->proveedor = Proveedor::findOrFail($id);
        $this->fill($this->proveedor->only('ruc', 'razon_social', 'telefono', 'email'));
        $this->resetValidation();
    }
};
?>

<div>
    @if (session('ok'))
        <p class="text-green-700">{{ session('ok') }}</p>
    @endif

    <form wire:submit="guardar" class="space-y-4">
        <h2>{{ $proveedor ? 'Editar proveedor' : 'Nuevo proveedor' }}</h2>

        <div>
            <label for="ruc">RUC</label>
            <input id="ruc" type="text" wire:model.blur="ruc">
            @error('ruc')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="razon_social">Razón Social</label>
            <input id="razon_social" type="text" wire:model.blur="razon_social">
            @error('razon_social')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="telefono">Teléfono</label>
            <input id="telefono" type="text" wire:model.blur="telefono">
            @error('telefono')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email">Correo electrónico</label>
            <input id="email" type="email" wire:model.blur="email">
            @error('email')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">Guardar</button>

        @if ($proveedor)
            <button type="button" wire:click="cancelar">Cancelar</button>
        @endif
    </form>
</div>