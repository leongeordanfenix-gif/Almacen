<?php

use App\Models\Proveedor;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;
    public $search = '';

    #[On('proveedor-guardado')]
    public function refrescar()
    {
    }

    public function with(): array
    {
        $consulta = Proveedor::query();

        if ($this->search) {
            $consulta->where('razon_social', 'like', '%' . $this->search . '%')
                     ->orWhere('ruc', 'like', '%' . $this->search . '%');
        }

        return [
            'proveedores' => $consulta->orderBy('razon_social')->paginate(10),
        ];
    }
};
?>

<div class="bg-white p-4 rounded-lg shadow">
    {{-- Encabezado gris de búsqueda --}}
    <div class="bg-gray-200 p-3 rounded-md mb-4 flex flex-wrap gap-3 items-center border border-gray-300">
        <input type="text"
            wire:model.live.debounce.400ms="search"
            placeholder="Buscar por razón social o RUC"
            class="bg-gray-100 border border-gray-300 text-gray-800 text-sm rounded px-3 py-2 w-full focus:outline-none focus:bg-white focus:border-gray-500">
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead class="bg-gray-200 text-gray-700 uppercase text-xs border-b border-gray-300">
                <tr>
                    <th class="py-2.5 px-3">RUC</th>
                    <th class="py-2.5 px-3">Razón Social</th>
                    <th class="py-2.5 px-3">Teléfono</th>
                    <th class="py-2.5 px-3">Email</th>
                    <th class="py-2.5 px-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($proveedores as $proveedor)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-3 font-mono text-gray-700">{{ $proveedor->ruc }}</td>
                        <td class="py-2 px-3 text-gray-900">{{ $proveedor->razon_social }}</td>
                        <td class="py-2 px-3 text-gray-700">{{ $proveedor->telefono }}</td>
                        <td class="py-2 px-3 text-gray-700">{{ $proveedor->email }}</td>
                        <td class="py-2 px-3">
                            <button wire:click="$dispatch('editar-proveedor', { id: {{ $proveedor->id }} })"
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
        {{ $proveedores->links() }}
    </div>
</div>