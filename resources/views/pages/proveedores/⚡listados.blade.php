<?php

use Livewire\Component;

new class extends Component {
};
?>

<div class="container mx-auto grid grid-cols-6 py-10 px-6 mt-7 gap-6">
    <div class="col-span-3">
        @livewire('proveedores-index')
    </div>
    <div class="col-span-3">
        @livewire('proveedores-form')
    </div>
</div>