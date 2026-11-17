<?php
use App\Models\Producto;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public ?Producto $producto = null;
    public $codigo = '';
    public $nombre = '';
    public $descripcion = '';
    public $precio = '';

    protected function rules(): array
    {
        return [
            'codigo' => [
                'required', 'max:20', 'alpha_dash',
                Rule::unique('productos', 'codigo')->ignore($this->producto?->id)
            ],
            'nombre' => ['required', 'max:100'],
            'descripcion' => ['required', 'max:500'],
            'precio' => ['required', 'numeric', 'gt:0', 'max:999999.99', 'decimal:0,2'],
        ];
    }
    
    protected function messages(): array
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

    // Paso 4 + Caso borde: limpia espacios en código y valida en tiempo real al salir del input
    public function updated($propiedad)
    {
        if ($propiedad === 'codigo') {
            $this->codigo = trim((string) $this->codigo);
        }
        $this->validateOnly($propiedad);
    }

    // Paso 6: Crea o actualiza el registro
    public function guardar()
    {
        $datos = $this->validate();

        if ($this->producto) {
            $this->producto->update($datos);
        } else {
            Producto::create($datos);
        }

        $this->cancelar();
        session()->flash('ok', 'Producto guardado correctamente.');
        $this->dispatch('producto-guardado');
    }

    public function cancelar()
    {
        $this->reset();
        $this->resetValidation();
    }

    // Paso 7: Escucha el clic en "Editar" desde la tabla y carga los datos
    #[On('editar-producto')]
    public function editar($id)
    {
        $this->producto = Producto::findOrFail($id);
        $this->fill($this->producto->only('codigo', 'nombre', 'descripcion', 'precio'));
        $this->resetValidation();
    }
};
?>

<!-- Paso 5: Vista Blade -->
<div>
    @if (session('ok'))
        <p class="text-green-700">{{ session('ok') }}</p>
    @endif

    <form wire:submit="guardar" class="space-y-4">
        <h2>{{ $producto ? 'Editar producto' : 'Nuevo producto' }}</h2>

        <div>
            <label for="codigo">Código</label>
            <input id="codigo" type="text" wire:model.blur="codigo">
            @error('codigo')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="nombre">Nombre</label>
            <input id="nombre" type="text" wire:model.blur="nombre">
            @error('nombre')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" wire:model.blur="descripcion"></textarea>
            @error('descripcion')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="precio">Precio</label>
            <input id="precio" type="text" wire:model.blur="precio">
            @error('precio')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">Guardar</button>

        @if ($producto)
            <button type="button" wire:click="cancelar">Cancelar</button>
        @endif
    </form>
</div>