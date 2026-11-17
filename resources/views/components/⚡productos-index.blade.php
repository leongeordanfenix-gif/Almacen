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
            $consulta->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('codigo', 'like', '%' . $this->search . '%');
            });
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

<div class="bg-white p-4 rounded-lg shadow">
    {{-- Encabezado gris de búsqueda y filtros --}}
    <div class="bg-gray-200 p-3 rounded-md mb-4 flex flex-wrap gap-3 items-center border border-gray-300">
        <input type="text"
            wire:model.live.debounce.400ms="search"
            placeholder="Buscar por nombre o código"
            class="bg-gray-100 border border-gray-300 text-gray-800 text-sm rounded px-3 py-2 flex-1 focus:outline-none focus:bg-white focus:border-gray-500">

        <select wire:model.live="almacen_id"
            class="bg-gray-100 border border-gray-300 text-gray-800 text-sm rounded px-3 py-2 focus:outline-none focus:bg-white focus:border-gray-500">
            <option value="">Todos los almacenes</option>
            @foreach($almacenes as $almacen)
                <option value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
            @endforeach
        </select>

        <select wire:model.live="filtro_stock"
            class="bg-gray-100 border border-gray-300 text-gray-800 text-sm rounded px-3 py-2 focus:outline-none focus:bg-white focus:border-gray-500">
            <option value="">Estado de stock (Todos)</option>
            <option value="disponible">Con Stock</option>
            <option value="agotado">Agotados</option>
        </select>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead class="bg-gray-200 text-gray-700 uppercase text-xs border-b border-gray-300">
                <tr>
                    <th class="py-2.5 px-3">Código</th>
                    <th class="py-2.5 px-3">Nombre</th>
                    <th class="py-2.5 px-3">Precio</th>
                    <th class="py-2.5 px-3">Stock</th>
                    <th class="py-2.5 px-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($productos as $producto)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-3 font-mono text-gray-700">{{ $producto->codigo }}</td>
                        <td class="py-2 px-3 text-gray-900">{{ $producto->nombre }}</td>
                        <td class="py-2 px-3 text-gray-700">S/ {{ number_format((float)$producto->precio, 2) }}</td>
                        <td class="py-2 px-3 text-gray-700">{{ $producto->cantidad->first()?->stock ?? 0 }}</td>
                        <td class="py-2 px-3">
                            <button wire:click="$dispatch('editar-producto', { id: {{ $producto->id }} })"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium px-3 py-1 rounded text-xs">
                                Editar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 px-3 text-center text-gray-500 bg-gray-50">Sin datos</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $productos->links() }}
    </div>
</div>