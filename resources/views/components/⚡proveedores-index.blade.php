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

<div>
    <div class="bg-gray-200 p-3 rounded" style="margin-bottom: 20px; display: flex; gap: 10px;">
        <input type="text"
            wire:model.live.debounce.400ms="search"
            placeholder="Buscar por razón social o RUC">
    </div>

    <table>
        <thead>
            <tr>
                <th>RUC</th>
                <th>Razón Social</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($proveedores as $proveedor)
                <tr>
                    <td>{{ $proveedor->ruc }}</td>
                    <td>{{ $proveedor->razon_social }}</td>
                    <td>{{ $proveedor->telefono }}</td>
                    <td>{{ $proveedor->email }}</td>
                    <td>
                        <button wire:click="$dispatch('editar-proveedor', { id: {{ $proveedor->id }} })">
                            Editar
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Sin datos</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $proveedores->links() }}
</div>