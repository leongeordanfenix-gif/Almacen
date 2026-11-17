<?php

use Livewire\Component;
use App\Models\Producto;
use App\Models\Almacen;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\On; // <-- Importante para escuchar eventos
    
new class extends Component {
    use WithPagination;
    public $search = '';
    public $categoria_id = '';
    public $almacen_id = '';
    public $filtro_stock = '';

    // Paso 7: Refresca la tabla cuando el formulario guarda un producto
    #[On('producto-guardado')]
    public function refrescar()
    {
    }

    public function with(): array
    {
        $consulta = Producto::with(['cantidad.almacen']);
        
        if ($this->search) {
            $consulta->where('nombre', 'like', '%' . $this->search . '%');
        }

        if ($this->categoria_id) {
            $consulta->where('categoria_id', $this->categoria_id);
        }

        if ($this->almacen_id) {
            $consulta->join('stock__almacen', 'productos.id', '=', 'stock__almacen.producto_id')
                     ->where('stock__almacen.almacen_id', $this->almacen_id)
                     ->select('productos.*'); // Evita colisión de IDs al editar
        }

        if ($this->filtro_stock === 'agotado') {
            $consulta->whereHas('cantidad', function($q) {
                $q->where('stock', '<=', 0);
            });
        } elseif ($this->filtro_stock === 'disponible') {
            $consulta->whereHas('cantidad', function($q) {
                $q->where('stock', '>', 0);
            });
        }

        return [
            'productos' => $consulta->orderBy('nombre')->paginate(10),
            'almacenes' => Almacen::all(),
        ];
    }
};
?>

<div>
    <div class="bg-gray-200 p-3 rounded" style="margin-bottom: 20px; display: flex; gap: 10px;">
        <input type="text"
            wire:model.live.debounce.400ms="search"
            placeholder="Buscar por nombre o código">
        <select wire:model.live="almacen_id">
            <option value="">Todos los almacenes</option>
            @foreach($almacenes as $almacen)
                <option value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
            @endforeach
        </select>
        <select wire:model.live="filtro_stock">
            <option value="">Estado de stock (Todos)</option>
            <option value="disponible">Con Stock</option>
            <option value="agotado">Agotados</option>
        </select>
    </div>

    <table>
        @forelse ($productos as $producto)
            <tr>
                <td>{{ $producto->codigo }}</td>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->cantidad->first()?->stock }}</td>
                <td>
                    <button wire:click="$dispatch('editar-producto', { id: {{ $producto->id }} })">
                        Editar
                    </button>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">Sin datos</td></tr>
        @endforelse
    </table>
    {{ $productos->links() }}
</div>