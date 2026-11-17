<?php

use Livewire\Component;

new class extends Component
{
//     public function productos(){
//         return Producto::with(['cantidad.almacen'])->find();
//     }
};
?>

<div class="container mx-auto grid grid-cols-6 py-10 px-6 mt-7 gap-6">
    <div class="col-span-3">
        @livewire('productos-index')
    </div>
    <div class="col-span-3">
        @livewire('productos-form')
    </div>
</div>